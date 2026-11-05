<?php
/**
 * =====================================================
 * WAHA (WhatsApp HTTP API) Service
 * =====================================================
 * Integration for WhatsApp Gateway powered by WAHA
 * Repository: https://github.com/devlikeapro/waha.git
 * =====================================================
 */

require_once __DIR__ . '/../config/database.php';

class WhatsAppService
{
    /**
     * Get WhatsApp Gateway settings from database
     */
    public static function getSettings(): array
    {
        $defaultSettings = [
            'id' => 1,
            'is_enabled' => 1,
            'api_url' => 'http://localhost:3000',
            'api_key' => '',
            'session_name' => 'default',
            'admin_phone' => '6281234567890',
            'notify_admin_on_order' => 1,
            'notify_admin_on_contact' => 1,
            'notify_customer_on_order' => 1,
            'notify_customer_on_paid' => 1,
            'order_template' => "Halo *{customer_name}*, terima kasih telah memesan di *{store_name}*! 🎉\n\nNomor Pesanan: *#{order_number}*\nProduk: *{product_name}*\nTotal: *Rp {amount}*\nMetode Pembayaran: *{payment_method}*\n\nSilakan selesaikan pembayaran sesuai petunjuk invoice Anda. Hubungi kami jika butuh bantuan. Terima kasih! 🙏",
            'paid_template' => "Pembayaran Dikonfirmasi! ✅\n\nHalo *{customer_name}*, pembayaran untuk pesanan *#{order_number}* (*{product_name}*) telah kami verifikasi dengan sukses!\n\nLink Akses / Download:\n🔗 {download_link}\nKode Lisensi: `{license_key}`\n\nTerima kasih telah mempercayai *{store_name}*! 🚀",
            'contact_template' => "📩 *Pesan Baru dari Website {store_name}*\n\nDari: *{contact_name}*\nEmail: {contact_email}\nNo. HP: {contact_phone}\nSubjek: *{contact_subject}*\n\nPesan:\n\"{contact_message}\""
        ];

        try {
            $pdo = getSafeConnection();
            if ($pdo) {
                $stmt = $pdo->query("SELECT * FROM `whatsapp_settings` WHERE `id` = 1 LIMIT 1");
                $row = $stmt->fetch();
                if ($row) {
                    return array_merge($defaultSettings, $row);
                }
            }
        } catch (\Throwable $e) {
            error_log('WhatsAppService::getSettings error: ' . $e->getMessage());
        }

        return $defaultSettings;
    }

    /**
     * Save settings to database
     */
    public static function saveSettings(array $data): bool
    {
        try {
            $pdo = getSafeConnection();
            if (!$pdo) return false;

            $stmt = $pdo->prepare("
                UPDATE `whatsapp_settings` SET
                    `is_enabled` = :is_enabled,
                    `api_url` = :api_url,
                    `api_key` = :api_key,
                    `session_name` = :session_name,
                    `admin_phone` = :admin_phone,
                    `notify_admin_on_order` = :notify_admin_on_order,
                    `notify_admin_on_contact` = :notify_admin_on_contact,
                    `notify_customer_on_order` = :notify_customer_on_order,
                    `notify_customer_on_paid` = :notify_customer_on_paid,
                    `order_template` = :order_template,
                    `paid_template` = :paid_template,
                    `contact_template` = :contact_template
                WHERE `id` = 1
            ");

            return $stmt->execute([
                ':is_enabled' => !empty($data['is_enabled']) ? 1 : 0,
                ':api_url' => rtrim(trim($data['api_url'] ?? 'http://localhost:3000'), '/'),
                ':api_key' => trim($data['api_key'] ?? ''),
                ':session_name' => trim($data['session_name'] ?? 'default') ?: 'default',
                ':admin_phone' => self::normalizeCleanNumber($data['admin_phone'] ?? ''),
                ':notify_admin_on_order' => !empty($data['notify_admin_on_order']) ? 1 : 0,
                ':notify_admin_on_contact' => !empty($data['notify_admin_on_contact']) ? 1 : 0,
                ':notify_customer_on_order' => !empty($data['notify_customer_on_order']) ? 1 : 0,
                ':notify_customer_on_paid' => !empty($data['notify_customer_on_paid']) ? 1 : 0,
                ':order_template' => trim($data['order_template'] ?? ''),
                ':paid_template' => trim($data['paid_template'] ?? ''),
                ':contact_template' => trim($data['contact_template'] ?? '')
            ]);
        } catch (\Throwable $e) {
            error_log('WhatsAppService::saveSettings error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Clean and normalize phone number to standard international format (e.g. 6281234567890)
     */
    public static function normalizeCleanNumber(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        }
        return $clean;
    }

    /**
     * Format phone number as WAHA chatId (e.g. 6281234567890@c.us)
     */
    public static function formatChatId(string $phone): string
    {
        if (str_ends_with($phone, '@c.us') || str_ends_with($phone, '@g.us')) {
            return $phone;
        }
        $number = self::normalizeCleanNumber($phone);
        return $number ? $number . '@c.us' : '';
    }

    /**
     * Helper to perform HTTP request to WAHA API
     * Supports both cURL and native stream context (file_get_contents) fallback
     */
    protected static function request(string $endpoint, string $method = 'GET', ?array $payload = null, int $timeout = 6): array
    {
        $settings = self::getSettings();
        $baseUrl = rtrim($settings['api_url'] ?: 'http://localhost:3000', '/');
        $url = $baseUrl . '/' . ltrim($endpoint, '/');

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json, image/png, */*'
        ];

        if (!empty($settings['api_key'])) {
            $headers[] = 'X-Api-Key: ' . $settings['api_key'];
        }

        // Method 1: cURL (Preferred when extension enabled)
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                if ($payload !== null) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                }
            } elseif ($method !== 'GET') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($response === false) {
                return [
                    'success' => false,
                    'http_code' => 0,
                    'error' => $error ?: 'Koneksi ke WAHA Server gagal atau timeout.',
                    'data' => null
                ];
            }

            // Handle binary image response (e.g. QR code png)
            if (str_contains(strtolower($contentType ?? ''), 'image/')) {
                return [
                    'success' => $httpCode >= 200 && $httpCode < 300,
                    'http_code' => $httpCode,
                    'is_image' => true,
                    'content_type' => $contentType,
                    'raw_image' => $response,
                    'data_uri' => 'data:' . $contentType . ';base64,' . base64_encode($response)
                ];
            }

            $decoded = json_decode($response, true);
            return [
                'success' => $httpCode >= 200 && $httpCode < 300,
                'http_code' => $httpCode,
                'data' => $decoded !== null ? $decoded : $response,
                'error' => ($httpCode >= 400) ? (is_array($decoded) ? ($decoded['message'] ?? ($decoded['error'] ?? 'WAHA error')) : 'HTTP ' . $httpCode) : null
            ];
        }

        // Method 2: Native Stream Context (file_get_contents) fallback
        try {
            $headerStr = implode("\r\n", $headers);
            $opts = [
                'http' => [
                    'method' => $method,
                    'header' => $headerStr,
                    'timeout' => $timeout,
                    'ignore_errors' => true
                ]
            ];
            if ($method === 'POST' && $payload !== null) {
                $opts['http']['content'] = json_encode($payload);
            }

            $context = stream_context_create($opts);
            $response = @file_get_contents($url, false, $context);

            $httpCode = 0;
            $contentType = '';
            if (isset($http_response_header) && is_array($http_response_header)) {
                if (preg_match('#HTTP/\S+\s+(\d+)#', $http_response_header[0], $m)) {
                    $httpCode = (int)$m[1];
                }
                foreach ($http_response_header as $hdr) {
                    if (stripos($hdr, 'Content-Type:') === 0) {
                        $contentType = trim(substr($hdr, 13));
                        break;
                    }
                }
            }

            if ($response === false && $httpCode === 0) {
                return [
                    'success' => false,
                    'http_code' => 0,
                    'error' => 'Koneksi ke WAHA Server gagal atau timeout.',
                    'data' => null
                ];
            }

            if (str_contains(strtolower($contentType), 'image/')) {
                return [
                    'success' => $httpCode >= 200 && $httpCode < 300,
                    'http_code' => $httpCode,
                    'is_image' => true,
                    'content_type' => $contentType,
                    'raw_image' => $response,
                    'data_uri' => 'data:' . $contentType . ';base64,' . base64_encode($response)
                ];
            }

            $decoded = json_decode($response, true);
            return [
                'success' => $httpCode >= 200 && $httpCode < 300,
                'http_code' => $httpCode,
                'data' => $decoded !== null ? $decoded : $response,
                'error' => ($httpCode >= 400) ? (is_array($decoded) ? ($decoded['message'] ?? ($decoded['error'] ?? 'WAHA error')) : 'HTTP ' . $httpCode) : null
            ];
        } catch (\Throwable $t) {
            return [
                'success' => false,
                'http_code' => 0,
                'error' => $t->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Check WAHA connection and session status
     */
    public static function checkStatus(): array
    {
        $settings = self::getSettings();
        $sessionName = $settings['session_name'] ?: 'default';

        // 1. Get all sessions
        $res = self::request('/api/sessions?all=true', 'GET', null, 4);
        if (!$res['success'] && $res['http_code'] === 0) {
            return [
                'connected' => false,
                'status' => 'OFFLINE',
                'message' => 'WAHA Server tidak dapat dihubungi di ' . $settings['api_url'],
                'session' => null,
                'error' => $res['error']
            ];
        }

        $sessions = is_array($res['data']) ? $res['data'] : [];
        $currentSession = null;
        foreach ($sessions as $s) {
            if (isset($s['name']) && $s['name'] === $sessionName) {
                $currentSession = $s;
                break;
            }
        }

        $status = $currentSession['status'] ?? 'NOT_FOUND';
        $me = $currentSession['me'] ?? null;

        return [
            'connected' => true,
            'status' => $status,
            'session_name' => $sessionName,
            'session' => $currentSession,
            'user' => $me,
            'all_sessions' => $sessions
        ];
    }

    /**
     * Fetch QR Code as Base64 Data URI if session is waiting for scan
     */
    public static function getQrCode(): array
    {
        $settings = self::getSettings();
        $session = $settings['session_name'] ?: 'default';

        // 1. Try endpoint /api/{session}/auth/qr
        $res = self::request("/api/{$session}/auth/qr", 'GET', null, 5);
        if ($res['success'] && !empty($res['data_uri'])) {
            return [
                'success' => true,
                'data_uri' => $res['data_uri']
            ];
        }

        // 2. Try screenshot endpoint as fallback
        $screenRes = self::request("/api/screenshot?session={$session}", 'GET', null, 5);
        if ($screenRes['success'] && !empty($screenRes['data_uri'])) {
            return [
                'success' => true,
                'data_uri' => $screenRes['data_uri']
            ];
        }

        return [
            'success' => false,
            'message' => 'QR Code belum siap atau sesi sudah aktif terhubung.'
        ];
    }

    /**
     * Start WAHA session
     */
    public static function startSession(): array
    {
        $settings = self::getSettings();
        $session = $settings['session_name'] ?: 'default';

        return self::request('/api/sessions/start', 'POST', [
            'name' => $session
        ], 8);
    }

    /**
     * Stop WAHA session
     */
    public static function stopSession(): array
    {
        $settings = self::getSettings();
        $session = $settings['session_name'] ?: 'default';

        return self::request('/api/sessions/stop', 'POST', [
            'name' => $session
        ], 8);
    }

    /**
     * Send plain text WhatsApp message via WAHA
     */
    public static function sendText(string $to, string $text): array
    {
        $settings = self::getSettings();
        if (empty($settings['is_enabled'])) {
            return ['success' => false, 'error' => 'Fitur WhatsApp Gateway dinonaktifkan di pengaturan.'];
        }

        $chatId = self::formatChatId($to);
        if (empty($chatId)) {
            return ['success' => false, 'error' => 'Nomor tujuan WhatsApp tidak valid.'];
        }

        $payload = [
            'session' => $settings['session_name'] ?: 'default',
            'chatId' => $chatId,
            'text' => $text
        ];

        return self::request('/api/sendText', 'POST', $payload, 8);
    }

    /**
     * Trigger notification on new order checkout
     */
    public static function sendOrderNotification(array $order): void
    {
        try {
            $settings = self::getSettings();
            if (empty($settings['is_enabled'])) return;

            $storeProfile = self::getStoreProfile();
            $storeName = $storeProfile['store_name'] ?? 'EduMarket';

            $replacements = [
                '{customer_name}' => $order['customer_name'] ?? 'Pelanggan',
                '{customer_phone}' => $order['customer_phone'] ?? '',
                '{order_number}' => $order['order_number'] ?? $order['id'] ?? '-',
                '{product_name}' => $order['product_name'] ?? 'Produk Digital',
                '{amount}' => number_format((float)($order['total_amount'] ?? $order['amount'] ?? 0), 0, ',', '.'),
                '{payment_method}' => strtoupper($order['payment_method'] ?? 'Transfer Bank / QRIS'),
                '{store_name}' => $storeName
            ];

            // 1. Notify Customer
            if (!empty($settings['notify_customer_on_order']) && !empty($order['customer_phone'])) {
                $template = $settings['order_template'] ?: "Halo *{customer_name}*, pesanan Anda *#{order_number}* telah diterima. Terima kasih!";
                $msg = str_replace(array_keys($replacements), array_values($replacements), $template);
                self::sendText($order['customer_phone'], $msg);
            }

            // 2. Notify Admin
            if (!empty($settings['notify_admin_on_order']) && !empty($settings['admin_phone'])) {
                $adminMsg = "🔔 *PESANAN BARU DITERIMA*\n\n"
                    . "No. Order: *#{$replacements['{order_number}']}*\n"
                    . "Pelanggan: *{$replacements['{customer_name}']}* ({$replacements['{customer_phone}']})\n"
                    . "Produk: *{$replacements['{product_name}']}*\n"
                    . "Total: *Rp {$replacements['{amount}']}*\n"
                    . "Metode: *{$replacements['{payment_method}']}*\n"
                    . "Status: *MENUNGGU PEMBAYARAN*\n\n"
                    . "Silakan cek dashboard admin untuk memproses.";
                self::sendText($settings['admin_phone'], $adminMsg);
            }
        } catch (\Throwable $e) {
            error_log('WhatsAppService::sendOrderNotification error: ' . $e->getMessage());
        }
    }

    /**
     * Trigger notification when order status is marked as 'paid'
     */
    public static function sendPaidNotification(array $order): void
    {
        try {
            $settings = self::getSettings();
            if (empty($settings['is_enabled'])) return;

            if (empty($settings['notify_customer_on_paid']) || empty($order['customer_phone'])) {
                return;
            }

            $storeProfile = self::getStoreProfile();
            $storeName = $storeProfile['store_name'] ?? 'EduMarket';

            // Generate clean download link if product has file
            $downloadLink = !empty($order['download_url']) 
                ? $order['download_url'] 
                : (!empty($order['product_slug']) ? 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/downloads/' . $order['product_slug'] : 'Hubungi Admin untuk link unduhan');

            $licenseKey = !empty($order['license_key']) ? $order['license_key'] : 'EDUMKT-' . strtoupper(substr(md5($order['order_number'] ?? uniqid()), 0, 12));

            $replacements = [
                '{customer_name}' => $order['customer_name'] ?? 'Pelanggan',
                '{order_number}' => $order['order_number'] ?? $order['id'] ?? '-',
                '{product_name}' => $order['product_name'] ?? 'Produk Digital',
                '{amount}' => number_format((float)($order['total_amount'] ?? $order['amount'] ?? 0), 0, ',', '.'),
                '{download_link}' => $downloadLink,
                '{license_key}' => $licenseKey,
                '{store_name}' => $storeName
            ];

            $template = $settings['paid_template'] ?: "Pembayaran untuk pesanan *#{order_number}* telah diterima. Link: {download_link} Lisensi: {license_key}";
            $msg = str_replace(array_keys($replacements), array_values($replacements), $template);

            self::sendText($order['customer_phone'], $msg);
        } catch (\Throwable $e) {
            error_log('WhatsAppService::sendPaidNotification error: ' . $e->getMessage());
        }
    }

    /**
     * Trigger notification when new contact message is received
     */
    public static function sendContactNotification(array $contact): void
    {
        try {
            $settings = self::getSettings();
            if (empty($settings['is_enabled']) || empty($settings['notify_admin_on_contact']) || empty($settings['admin_phone'])) {
                return;
            }

            $storeProfile = self::getStoreProfile();
            $storeName = $storeProfile['store_name'] ?? 'EduMarket';

            $replacements = [
                '{contact_name}' => $contact['name'] ?? 'Pengunjung',
                '{contact_email}' => $contact['email'] ?? '-',
                '{contact_phone}' => $contact['phone'] ?? '-',
                '{contact_subject}' => $contact['subject'] ?? 'Pesan Umum',
                '{contact_message}' => $contact['message'] ?? '-',
                '{store_name}' => $storeName
            ];

            $template = $settings['contact_template'] ?: "📩 Pesan baru dari {contact_name}: {contact_message}";
            $msg = str_replace(array_keys($replacements), array_values($replacements), $template);

            self::sendText($settings['admin_phone'], $msg);
        } catch (\Throwable $e) {
            error_log('WhatsAppService::sendContactNotification error: ' . $e->getMessage());
        }
    }

    /**
     * Helper to get store profile
     */
    protected static function getStoreProfile(): array
    {
        try {
            $pdo = getSafeConnection();
            if ($pdo) {
                $stmt = $pdo->query("SELECT * FROM `store_profile` WHERE `id` = 1 LIMIT 1");
                $res = $stmt->fetch();
                if ($res) return $res;
            }
        } catch (\Throwable $e) {}
        return ['store_name' => 'EduMarket'];
    }
}
