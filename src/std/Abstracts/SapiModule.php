<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/9/14
 * Time: 2:01
 */

namespace fibphp\std\Abstracts;

/*

struct _sapi_module_struct {
	char *name;
	char *pretty_name;

	int (*startup)(struct _sapi_module_struct *sapi_module);
	int (*shutdown)(struct _sapi_module_struct *sapi_module);

	int (*activate)(void);
	int (*deactivate)(void);

	size_t (*ub_write)(const char *str, size_t str_length);
	void (*flush)(void *server_context);
	zend_stat_t *(*get_stat)(void);
	char *(*getenv)(char *name, size_t name_len);

	void (*sapi_error)(int type, const char *error_msg, ...) ZEND_ATTRIBUTE_FORMAT(printf, 2, 3);

	int (*header_handler)(sapi_header_struct *sapi_header, sapi_header_op_enum op, sapi_headers_struct *sapi_headers);
	int (*send_headers)(sapi_headers_struct *sapi_headers);
	void (*send_header)(sapi_header_struct *sapi_header, void *server_context);

	size_t (*read_post)(char *buffer, size_t count_bytes);
	char *(*read_cookies)(void);

	void (*register_server_variables)(zval *track_vars_array);
	void (*log_message)(char *message, int syslog_type_int);
	double (*get_request_time)(void);
	void (*terminate_process)(void);

	char *php_ini_path_override;

	void (*default_post_reader)(void);
	void (*treat_data)(int arg, char *str, zval *destArray);
	char *executable_location;

	int php_ini_ignore;
	int php_ini_ignore_cwd; // don't look for php.ini in the current directory

    int (*get_fd)(int *fd);

	int (*force_http_10)(void);

	int (*get_target_uid)(uid_t *);
	int (*get_target_gid)(gid_t *);

	unsigned int (*input_filter)(int arg, char *var, char **val, size_t val_len, size_t *new_val_len);

	void (*ini_defaults)(HashTable *configuration_hash);
	int phpinfo_as_text;

	char *ini_entries;
	const zend_function_entry *additional_functions;
	unsigned int (*input_filter_init)(void);
};


typedef struct {
	char *header;
	size_t header_len;
} sapi_header_struct;


typedef struct {
	zend_llist headers;
	int http_response_code;
	unsigned char send_default_content_type;
	char *mimetype;
	char *http_status_line;
} sapi_headers_struct;

struct _sapi_post_entry {
	char *content_type;
	uint32_t content_type_len;
	void (*post_reader)(void);
	void (*post_handler)(char *content_type_dup, void *arg);
};

typedef struct {
	char *line; // If you allocated this, you need to free it yourself
    size_t line_len;
	zend_long response_code; // long due to zend_parse_parameters compatibility
} sapi_header_line;

typedef enum {					// Parameter:
    SAPI_HEADER_REPLACE,		// sapi_header_line*
	SAPI_HEADER_ADD,			// sapi_header_line*
	SAPI_HEADER_DELETE,			// sapi_header_line*
	SAPI_HEADER_DELETE_ALL,		// void
	SAPI_HEADER_SET_STATUS		// int
} sapi_header_op_enum;

typedef struct {
	const char *request_method;
	char *query_string;
	char *cookie_data;
	zend_long content_length;

	char *path_translated;
	char *request_uri;

	// Do not use request_body directly, but the php://input stream wrapper instead
struct _php_stream *request_body;

	const char *content_type;

	zend_bool headers_only;
	zend_bool no_headers;
	zend_bool headers_read;

	sapi_post_entry *post_entry;

	char *content_type_dup;

	// for HTTP authentication
	char *auth_user;
	char *auth_password;
	char *auth_digest;

	// this is necessary for the CGI SAPI module
	char *argv0;

	char *current_user;
	int current_user_length;

	// this is necessary for CLI module
	int argc;
	char **argv;
	int proto_num;
} sapi_request_info;


typedef struct _sapi_globals_struct {
    void *server_context;
    sapi_request_info request_info;
	sapi_headers_struct sapi_headers;
	int64_t read_post_bytes;
	unsigned char post_read;
	unsigned char headers_sent;
	zend_stat_t global_stat;
	char *default_mimetype;
	char *default_charset;
	HashTable *rfc1867_uploaded_files;
	zend_long post_max_size;
	int options;
	zend_bool sapi_started;
	double global_request_time;
	HashTable known_post_content_types;
	zval callback_func;
	zend_fcall_info_cache fci_cache;
} sapi_globals_struct;

typedef struct _sapi_post_entry sapi_post_entry;
typedef struct _sapi_module_struct sapi_module_struct;
*/

use fibphp\Plugin\ZendStat;

abstract class SapiModule
{
    // char *name;
    public static $name = '';

    // char *pretty_name;
    public static $pretty_name = '';

    // int (*startup)(struct _sapi_module_struct *sapi_module);
    abstract static function startup(SapiModule $sapi_module): int;

    // int (*shutdown)(struct _sapi_module_struct *sapi_module);
    abstract static function shutdown(SapiModule $sapi_module): int;

    // int (*activate)(void);
    abstract static function activate(): int;

    // int (*deactivate)(void);
    abstract static function deactivate(): int;

    // size_t (*ub_write)(const char *str, size_t str_length);
    abstract static function ub_write(string $str, int $str_length): int;

    // void (*flush)(void *server_context);
    abstract static function flush($server_context): void;

    // zend_stat_t *(*get_stat)(void);
    abstract static function get_stat(): ZendStat;

    // char *(*getenv)(char *name, size_t name_len);
    abstract static function getenv(string $name, int $name_len): string;

    // void (*sapi_error)(int type, const char *error_msg, ...) ZEND_ATTRIBUTE_FORMAT(printf, 2, 3);
    abstract static function sapi_error(int $type, string $error_msg__FIB_Ellipsis_BIF__): void;

    // int (*header_handler)(sapi_header_struct *sapi_header, sapi_header_op_enum op, sapi_headers_struct *sapi_headers);
    abstract static function header_handler($sapi_header, int $op, $sapi_headers): int;

    // int (*send_headers)(sapi_headers_struct *sapi_headers);
    abstract static function send_headers($sapi_headers): void;

    // void (*send_header)(sapi_header_struct *sapi_header, void *server_context);
    abstract static function send_header($sapi_header, $server_context): void;

    // size_t (*read_post)(char *buffer, size_t count_bytes);
    abstract static function read_post(string $buffer, int $count_bytes): int;

    // char *(*read_cookies)(void);
    abstract static function read_cookies(): string;

    // void (*register_server_variables)(zval *track_vars_array);
    abstract static function register_server_variables($track_vars_array): void;

    // void (*log_message)(char *message, int syslog_type_int);
    abstract static function log_message(string $message, int $syslog_type_int): void;

    // double (*get_request_time)(void);
    abstract static function get_request_time(): float;

    // void (*terminate_process)(void);
    abstract static function terminate_process(): void;

    // char *php_ini_path_override;
    public static $php_ini_path_override = '';

    // void (*default_post_reader)(void);
    abstract static function default_post_reader(): void;

    // void (*treat_data)(int arg, char *str, zval *destArray);
    abstract static function treat_data(int $arg, string $str, $destArray): void;

    // char *executable_location;
    public static $executable_location = '';

    // int php_ini_ignore;
    public static $php_ini_ignore = 0;

    // int php_ini_ignore_cwd; // don't look for php.ini in the current directory
    public static $php_ini_ignore_cwd = 0;

    // int (*get_fd)(int *fd);
    abstract public static function get_fd(int $fd): int;

    // int (*force_http_10)(void);
    abstract public static function force_http_10(): int;

    // int (*get_target_uid)(uid_t *);
    abstract public static function get_target_uid(int &$uid): int;

    // int (*get_target_gid)(gid_t *);
    abstract public static function get_target_gid(int &$gid): int;

    // unsigned int (*input_filter)(int arg, char *var, char **val, size_t val_len, size_t *new_val_len);

    // void (*ini_defaults)(HashTable *configuration_hash);
    // int phpinfo_as_text;

    // char *ini_entries;
    // const zend_function_entry *additional_functions;
    // unsigned int (*input_filter_init)(void);
}