<?php
class DiskCache implements Cache_Adapter {
	/** @var Cache_Adapter $adapter */
	private $adapter;

	/**
	 * https://stackoverflow.com/a/53662733
	 *
	 * @var array<string, string>
	 */
	private array $mimeMap = [
		'video/3gpp2'                                                               => '3g2',
		'video/3gp'                                                                 => '3gp',
		'video/3gpp'                                                                => '3gp',
		'application/x-compressed'                                                  => '7zip',
		'audio/x-acc'                                                               => 'aac',
		'audio/ac3'                                                                 => 'ac3',
		'application/postscript'                                                    => 'ai',
		'audio/x-aiff'                                                              => 'aif',
		'audio/aiff'                                                                => 'aif',
		'audio/x-au'                                                                => 'au',
		'video/x-msvideo'                                                           => 'avi',
		'video/msvideo'                                                             => 'avi',
		'video/avi'                                                                 => 'avi',
		'application/x-troff-msvideo'                                               => 'avi',
		'application/macbinary'                                                     => 'bin',
		'application/mac-binary'                                                    => 'bin',
		'application/x-binary'                                                      => 'bin',
		'application/x-macbinary'                                                   => 'bin',
		'image/bmp'                                                                 => 'bmp',
		'image/x-bmp'                                                               => 'bmp',
		'image/x-bitmap'                                                            => 'bmp',
		'image/x-xbitmap'                                                           => 'bmp',
		'image/x-win-bitmap'                                                        => 'bmp',
		'image/x-windows-bmp'                                                       => 'bmp',
		'image/ms-bmp'                                                              => 'bmp',
		'image/x-ms-bmp'                                                            => 'bmp',
		'application/bmp'                                                           => 'bmp',
		'application/x-bmp'                                                         => 'bmp',
		'application/x-win-bitmap'                                                  => 'bmp',
		'application/cdr'                                                           => 'cdr',
		'application/coreldraw'                                                     => 'cdr',
		'application/x-cdr'                                                         => 'cdr',
		'application/x-coreldraw'                                                   => 'cdr',
		'image/cdr'                                                                 => 'cdr',
		'image/x-cdr'                                                               => 'cdr',
		'zz-application/zz-winassoc-cdr'                                            => 'cdr',
		'application/mac-compactpro'                                                => 'cpt',
		'application/pkix-crl'                                                      => 'crl',
		'application/pkcs-crl'                                                      => 'crl',
		'application/x-x509-ca-cert'                                                => 'crt',
		'application/pkix-cert'                                                     => 'crt',
		'text/css'                                                                  => 'css',
		'text/x-comma-separated-values'                                             => 'csv',
		'text/comma-separated-values'                                               => 'csv',
		'application/vnd.msexcel'                                                   => 'csv',
		'application/x-director'                                                    => 'dcr',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
		'application/x-dvi'                                                         => 'dvi',
		'message/rfc822'                                                            => 'eml',
		'application/x-msdownload'                                                  => 'exe',
		'video/x-f4v'                                                               => 'f4v',
		'audio/x-flac'                                                              => 'flac',
		'video/x-flv'                                                               => 'flv',
		'image/gif'                                                                 => 'gif',
		'application/gpg-keys'                                                      => 'gpg',
		'application/x-gtar'                                                        => 'gtar',
		'application/x-gzip'                                                        => 'gzip',
		'application/mac-binhex40'                                                  => 'hqx',
		'application/mac-binhex'                                                    => 'hqx',
		'application/x-binhex40'                                                    => 'hqx',
		'application/x-mac-binhex40'                                                => 'hqx',
		'text/html'                                                                 => 'html',
		'image/x-icon'                                                              => 'ico',
		'image/x-ico'                                                               => 'ico',
		'image/vnd.microsoft.icon'                                                  => 'ico',
		'text/calendar'                                                             => 'ics',
		'application/java-archive'                                                  => 'jar',
		'application/x-java-application'                                            => 'jar',
		'application/x-jar'                                                         => 'jar',
		'image/jp2'                                                                 => 'jp2',
		'video/mj2'                                                                 => 'jp2',
		'image/jpx'                                                                 => 'jp2',
		'image/jpm'                                                                 => 'jp2',
		'image/jpeg'                                                                => 'jpg',
		'image/pjpeg'                                                               => 'jpg',
		'application/x-javascript'                                                  => 'js',
		'application/json'                                                          => 'json',
		'text/json'                                                                 => 'json',
		'application/vnd.google-earth.kml+xml'                                      => 'kml',
		'application/vnd.google-earth.kmz'                                          => 'kmz',
		'text/x-log'                                                                => 'log',
		'audio/x-m4a'                                                               => 'm4a',
		'audio/mp4'                                                                 => 'm4a',
		'application/vnd.mpegurl'                                                   => 'm4u',
		'audio/midi'                                                                => 'mid',
		'application/vnd.mif'                                                       => 'mif',
		'video/quicktime'                                                           => 'mov',
		'video/x-sgi-movie'                                                         => 'movie',
		'audio/mpeg'                                                                => 'mp3',
		'audio/mpg'                                                                 => 'mp3',
		'audio/mpeg3'                                                               => 'mp3',
		'audio/mp3'                                                                 => 'mp3',
		'video/mp4'                                                                 => 'mp4',
		'video/mpeg'                                                                => 'mpeg',
		'application/oda'                                                           => 'oda',
		'audio/ogg'                                                                 => 'ogg',
		'video/ogg'                                                                 => 'ogg',
		'application/ogg'                                                           => 'ogg',
		'font/otf'                                                                  => 'otf',
		'application/x-pkcs10'                                                      => 'p10',
		'application/pkcs10'                                                        => 'p10',
		'application/x-pkcs12'                                                      => 'p12',
		'application/x-pkcs7-signature'                                             => 'p7a',
		'application/pkcs7-mime'                                                    => 'p7c',
		'application/x-pkcs7-mime'                                                  => 'p7c',
		'application/x-pkcs7-certreqresp'                                           => 'p7r',
		'application/pkcs7-signature'                                               => 'p7s',
		'application/pdf'                                                           => 'pdf',
		'application/octet-stream'                                                  => 'pdf',
		'application/x-x509-user-cert'                                              => 'pem',
		'application/x-pem-file'                                                    => 'pem',
		'application/pgp'                                                           => 'pgp',
		'application/x-httpd-php'                                                   => 'php',
		'application/php'                                                           => 'php',
		'application/x-php'                                                         => 'php',
		'text/php'                                                                  => 'php',
		'text/x-php'                                                                => 'php',
		'application/x-httpd-php-source'                                            => 'php',
		'image/png'                                                                 => 'png',
		'image/x-png'                                                               => 'png',
		'application/powerpoint'                                                    => 'ppt',
		'application/vnd.ms-powerpoint'                                             => 'ppt',
		'application/vnd.ms-office'                                                 => 'ppt',
		'application/msword'                                                        => 'ppt',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
		'application/x-photoshop'                                                   => 'psd',
		'image/vnd.adobe.photoshop'                                                 => 'psd',
		'audio/x-realaudio'                                                         => 'ra',
		'audio/x-pn-realaudio'                                                      => 'ram',
		'application/x-rar'                                                         => 'rar',
		'application/rar'                                                           => 'rar',
		'application/x-rar-compressed'                                              => 'rar',
		'audio/x-pn-realaudio-plugin'                                               => 'rpm',
		'application/x-pkcs7'                                                       => 'rsa',
		'text/rtf'                                                                  => 'rtf',
		'text/richtext'                                                             => 'rtx',
		'video/vnd.rn-realvideo'                                                    => 'rv',
		'application/x-stuffit'                                                     => 'sit',
		'application/smil'                                                          => 'smil',
		'text/srt'                                                                  => 'srt',
		'image/svg+xml'                                                             => 'svg',
		'application/x-shockwave-flash'                                             => 'swf',
		'application/x-tar'                                                         => 'tar',
		'application/x-gzip-compressed'                                             => 'tgz',
		'image/tiff'                                                                => 'tiff',
		'font/ttf'                                                                  => 'ttf',
		'text/plain'                                                                => 'txt',
		'text/x-vcard'                                                              => 'vcf',
		'application/videolan'                                                      => 'vlc',
		'text/vtt'                                                                  => 'vtt',
		'audio/x-wav'                                                               => 'wav',
		'audio/wave'                                                                => 'wav',
		'audio/wav'                                                                 => 'wav',
		'application/wbxml'                                                         => 'wbxml',
		'video/webm'                                                                => 'webm',
		'image/webp'                                                                => 'webp',
		'audio/x-ms-wma'                                                            => 'wma',
		'application/wmlc'                                                          => 'wmlc',
		'video/x-ms-wmv'                                                            => 'wmv',
		'video/x-ms-asf'                                                            => 'wmv',
		'font/woff'                                                                 => 'woff',
		'font/woff2'                                                                => 'woff2',
		'application/xhtml+xml'                                                     => 'xhtml',
		'application/excel'                                                         => 'xl',
		'application/msexcel'                                                       => 'xls',
		'application/x-msexcel'                                                     => 'xls',
		'application/x-ms-excel'                                                    => 'xls',
		'application/x-excel'                                                       => 'xls',
		'application/x-dos_ms_excel'                                                => 'xls',
		'application/xls'                                                           => 'xls',
		'application/x-xls'                                                         => 'xls',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
		'application/vnd.ms-excel'                                                  => 'xlsx',
		'application/xml'                                                           => 'xml',
		'text/xml'                                                                  => 'xml',
		'text/xsl'                                                                  => 'xsl',
		'application/xspf+xml'                                                      => 'xspf',
		'application/x-compress'                                                    => 'z',
		'application/x-zip'                                                         => 'zip',
		'application/zip'                                                           => 'zip',
		'application/x-zip-compressed'                                              => 'zip',
		'application/s-compressed'                                                  => 'zip',
		'multipart/x-zip'                                                           => 'zip',
		'text/x-scriptzsh'                                                          => 'zsh'
	];

	public function __construct(string $dir) {
		foreach (PluginHost::getInstance()->get_plugins() as $n => $p) {
			if (implements_interface($p, "Cache_Adapter")) {

				/** @var Cache_Adapter $p */
				$this->adapter = $p;
				$this->adapter->set_dir($dir);
				return;
			}
		}

		$this->adapter = new Cache_Local();
		$this->adapter->set_dir($dir);
	}

	public function set_dir(string $dir) : void {
		$this->adapter->set_dir($dir);
	}

	public function make_dir(): bool {
		return $this->adapter->make_dir();
	}

	public function is_writable(?string $filename = null): bool {
		return $this->adapter->is_writable($filename);
	}

	public function exists(string $filename): bool {
		return $this->adapter->exists($filename);
	}

	public function get_size(string $filename) {
		return $this->adapter->get_size($filename);
	}

	public function put(string $filename, $data) {
		return $this->adapter->put($filename, $data);
	}

	public function touch(string $filename): bool {
		return $this->adapter->touch($filename);
	}

	public function get(string $filename): ?string {
		return $this->adapter->get($filename);
	}

	static function expire(): void {
		$adapter = new Cache_Local();
		$adapter->expire_all();
	}

	public function expire_all(): void {
		$this->adapter->expire_all();
	}

	public function get_dir(): string {
		return $this->adapter->get_dir();
	}

	/** Downloads $url to cache as $local_filename if its missing (unless $force-ed)
	 * @param string $url
	 * @param string $local_filename
	 * @param array<string,string|int|false> $options (additional params to UrlHelper::fetch())
	 * @param bool $force
	 * @return bool
	 */
	public function download(string $url, string $local_filename, array $options = [], bool $force = false) : bool {
		if ($this->exists($local_filename) && !$force)
			return true;

		$data = UrlHelper::fetch(array_merge(["url" => $url,
							"max_size" => Config::get(Config::MAX_CACHE_FILE_SIZE)], $options));

		if ($data)
			return $this->put($local_filename, $data) > 0;

		return false;
	}

	/**
	 * @return bool|int false if the file doesn't exist (or unreadable) or isn't audio/video, true if a plugin handled, otherwise int of bytes sent
	 */
	public function send(string $filename) {
		$mimetype = $this->adapter->get_mime_type($filename);

		if ($mimetype == "application/octet-stream")
				$mimetype = "video/mp4";

		# block SVG because of possible embedded javascript (.....)
		$mimetype_blacklist = [ "image/svg+xml" ];

		/* only serve video and images */
		if (!preg_match("/(image|audio|video)\//", (string)$mimetype) || in_array($mimetype, $mimetype_blacklist)) {
			http_response_code(400);
			header("Content-type: text/plain");

			print "Stored file has disallowed content type ($mimetype)";
			return false;
		}

		$fake_extension = $this->get_fake_extension($filename);

		if ($fake_extension)
			$fake_extension = ".$fake_extension";

		header("Content-Disposition: inline; filename=\"{$filename}{$fake_extension}\"");
		header("Content-type: $mimetype");

		return $this->adapter->send($filename);
	}

	public function get_full_path(string $filename): string {
		return $this->adapter->get_full_path($filename);
	}

	public function get_mime_type(string $filename) {
		return $this->adapter->get_mime_type($filename);
	}

	public function get_fake_extension(string $filename): string {
		$mimetype = $this->get_mime_type($filename);

		if ($mimetype)
			return isset($this->mimeMap[$mimetype]) ? $this->mimeMap[$mimetype] : "";
		else
			return "";
	}

	public function get_url(string $filename): string {
		return Config::get_self_url() . "/public.php?op=cached&file=" . basename($this->adapter->get_dir()) . "/" . basename($filename);
	}

	// check for locally cached (media) URLs and rewrite to local versions
	// this is called separately after sanitize() and plugin render article hooks to allow
	// plugins work on original source URLs used before caching
	// NOTE: URLs should be already absolutized because this is called after sanitize()
	static public function rewrite_urls(string $str): string {
		$res = trim($str);
		if (!$res) return '';

		$doc = new DOMDocument();
		if (@$doc->loadHTML('<?xml encoding="UTF-8">' . $res)) {
			$xpath = new DOMXPath($doc);
			$cache = new DiskCache("images");

			$entries = $xpath->query('(//img[@src]|//source[@src|@srcset]|//video[@poster|@src])');

			$need_saving = false;

			foreach ($entries as $entry) {
				foreach (array('src', 'poster') as $attr) {
					if ($entry->hasAttribute($attr)) {
						$url = $entry->getAttribute($attr);
						$cached_filename = sha1($url);

						if ($cache->exists($cached_filename)) {
							$url = $cache->get_url($cached_filename);

							$entry->setAttribute($attr, $url);
							$entry->removeAttribute("srcset");

							$need_saving = true;
						}
					}
				}

				if ($entry->hasAttribute("srcset")) {
					$matches = RSSUtils::decode_srcset($entry->getAttribute('srcset'));

					for ($i = 0; $i < count($matches); $i++) {
						$cached_filename = sha1($matches[$i]["url"]);

						if ($cache->exists($cached_filename)) {
							$matches[$i]["url"] = $cache->get_url($cached_filename);

							$need_saving = true;
						}
					}

					$entry->setAttribute("srcset", RSSUtils::encode_srcset($matches));
				}
			}

			if ($need_saving) {
				if (isset($doc->firstChild))
					$doc->removeChild($doc->firstChild); //remove doctype

				$res = $doc->saveHTML();
			}
		}
		return $res;
	}
}
