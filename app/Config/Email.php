<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'services@financeflow.my.id';
    public string $fromName   = 'FinanceFlow';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = 'mail.financeflow.my.id';

    /**
     * SMTP Username
     */
    // public string $SMTPUser = 'fnncflow@gmail.com';
    public string $SMTPUser = 'services@financeflow.my.id';

    /**
     * SMTP Password
     */
    public string $SMTPPass = '@Khadifar123';

    /**
     * SMTP Port
     */
    public int $SMTPPort = 587; // Port standar untuk TLS

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 60;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls'; // TLS untuk koneksi terenkripsi

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;

    // PENTING: Jika menggunakan Gmail, gunakan App Password (bukan password utama)!
    // Lihat https://support.google.com/accounts/answer/185833 untuk membuat App Password.
    // Jika menggunakan layanan email lain, pastikan SMTPHost, SMTPUser, SMTPPass, SMTPPort, SMTPCrypto sudah benar.
    // Untuk Gmail:
    //   - SMTPHost: smtp.gmail.com
    //   - SMTPPort: 465 (SSL) atau 587 (TLS)
    //   - SMTPCrypto: 'ssl' untuk 465, 'tls' untuk 587
    //   - SMTPUser: alamat email lengkap
    //   - SMTPPass: App Password (16 digit, bukan password utama)
    //
    // Jika email gagal terkirim, cek log di writable/logs/ untuk pesan error detail.
}
