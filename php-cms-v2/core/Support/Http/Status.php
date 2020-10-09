<?php
namespace Core\Support\Http;


/**
 * Http Status class
 * 
 * @package Core\Support\Http
 * @author  GEE
 */
class Status
{
    /**
     * Status code
     */
    // 1×× Informational
    const CONTINUE                          = 100;
    const SWITCHING_PROTOCOLS               = 101;
    const PROCESSING                        = 102;
    // 2×× Success
    const OK                                = 200;
    const CREATED                           = 201;
    const ACCEPTED                          = 202;
    const NONAUTHORITATIVE_INFORMATION      = 203;
    const NO_CONTENT                        = 204;
    const RESET_CONTENT                     = 205;
    const PARTIAL_CONTENT                   = 206;
    // 3×× Redirection
    const MULTIPLE_CHOICES                  = 300;
    const MOVED_PERMANENTLY                 = 301;
    const MOVED_TEMPORARILY                 = 302;
    const SEE_OTHER                         = 303;
    const NOT_MODIFIED                      = 304;
    const USE_PROXY                         = 305;
    // 4×× Client Error
    const BAD_REQUEST                       = 400;
    const UNAUTHORIZED                      = 401;
    const PAYMENT_REQUIRED                  = 402;
    const FORBIDDEN                         = 403;
    const NOT_FOUND                         = 404;
    const METHOD_NOT_ALLOWED                = 405;
    const NOT_ACCEPTABLE                    = 406;
    const PROXY_AUTHENTICATION_REQUIRED     = 407;
    const REQUEST_TIMEOUT                   = 408;
    const CONFLICT                          = 408;
    const GONE                              = 410;
    const LENGTH_REQUIRED                   = 411;
    const PRECONDITION_FAILED               = 412;
    const REQUEST_ENTITY_TOO_LARGE          = 413;
    const REQUESTURI_TOO_LARGE              = 414;
    const UNSUPPORTED_MEDIA_TYPE            = 415;
    const REQUESTED_RANGE_NOT_SATISFIABLE   = 416;
    const EXPECTATION_FAILED                = 417;
    const IM_A_TEAPOT                       = 418;
    // 5×× Server Error
    const INTERNAL_SERVER_ERROR             = 500;
    const NOT_IMPLEMENTED                   = 501;
    const BAD_GATEWAY                       = 502;
    const SERVICE_UNAVAILABLE               = 503;
    const GATEWAY_TIMEOUT                   = 504;
    const HTTP_VERSION_NOT_SUPPORTED        = 505;

    /**
     * Status messages
     * @link https://httpstatuses.com/
     * @var array
     */
    public static $list = [
        // 1×× Informational
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        
        // 2×× Success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',

        // 3×× Redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',

        // 4×× Client Error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',

        // 5×× Server Error
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    ];


    /**
     * Return valid status code
     * 
     * @param int $status Status code
     * @return int
     */
    public static function getCode($status)
    {
        return isset(self::$list[$status]) ? $status : self::BAD_REQUEST;
    }


    /**
     * Check if status is OK
     * 
     * @param int $status Status code
     * @return bool
     */
    public static function isOk($status)
    {
        return self::getCode($status) == self::OK;
    }


    /**
     * Check if status success
     * 
     * @param int $status Status code
     * @return bool
     */
    public static function isSuccess($status)
    {
        $status = self::getCode($status);
        return  $status >= self::OK && $status < self::MULTIPLE_CHOICES;
    }


    /**
     * Return status message by status code
     * 
     * @param int   $status     Status code
     * @param bool  $withCode   Return message with code prefix
     * @return string
     */
    public static function getMessage($status, $withCode = true)
    {
        $status = self::getCode($status);
        return ($withCode ? "{$status} " : "") . self::$list[$status];
    }
}
