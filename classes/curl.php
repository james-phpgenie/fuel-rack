<?php

class Request_Curl extends Fuel\Core\Request_Curl
{
	public function execute(array $additional_params = array())
	{
		// Reset response
		$this->response = null;
		$this->response_info = array();

		// Set two default options, and merge any extra ones in
		if ( ! isset($this->options[CURLOPT_TIMEOUT]))
		{
			$this->options[CURLOPT_TIMEOUT] = 30;
		}
		if ( ! isset($this->options[CURLOPT_RETURNTRANSFER]))
		{
			$this->options[CURLOPT_RETURNTRANSFER] = true;
		}
		if ( ! isset($this->options[CURLOPT_FAILONERROR]))
		{
			$this->options[CURLOPT_FAILONERROR] = true;
		}

		// Only set follow location if not running securely
		if ( ! ini_get('safe_mode') && ! ini_get('open_basedir'))
		{
			// Ok, follow location is not set already so lets set it to true
			if ( ! isset($this->options[CURLOPT_FOLLOWLOCATION]))
			{
				$this->options[CURLOPT_FOLLOWLOCATION] = true;
			}
		}

		if ( ! empty($this->headers))
		{
			$this->set_option(CURLOPT_HTTPHEADER, $this->get_headers());
		}

		$additional_params and $this->params = \Arr::merge($this->params, $additional_params);
		$this->method and $this->options[CURLOPT_CUSTOMREQUEST] = $this->method;

		if ( ! empty($this->method))
		{
			$this->options[CURLOPT_CUSTOMREQUEST] = $this->method;
			$this->{'method_'.strtolower($this->method)}();
		}
		else
		{
			$this->method_get();
		}

		$connection = $this->connection();

		curl_setopt_array($connection, $this->options);

		// Execute the request & and hide all output
		$body = curl_exec($connection);
		$this->response_info = curl_getinfo($connection);
		$mime = isset($this->headers['Accept']) ? $this->headers['Accept'] : $this->response_info('content_type', 'text/plain');

		// Was header data requested?
		$headers = array();
		if (isset($this->options[CURLOPT_HEADER]) and $this->options[CURLOPT_HEADER])
		{
			// Split the headers from the body
			$raw_headers = explode("\n", str_replace("\r", "", substr($body, 0, $this->response_info['header_size'])));
			$body = substr($body, $this->response_info['header_size']);

			// Convert the header data
			foreach ($raw_headers as $header)
			{
				$header = explode(':', $header, 2);
				if (isset($header[1]))
				{
					$headers[trim($header[0])] = trim($header[1]);
				}
			}
		}

		$this->set_response($body, $this->response_info('http_code', 200), $mime, $headers);

		// Request successful
		curl_close($connection);
		$this->set_defaults();

		return $this;
		
	}
	
	/**
	 * HEAD request
	 *
	 * @param   array  $params
	 * @return  void
	 */
	protected function method_head()
	{
		$params = is_array($this->params) ? $this->encode($this->params) : $this->params;

		$this->set_option(CURLOPT_POSTFIELDS, $params);

		// Override method, I think this makes $_POST DELETE data but... we'll see eh?
		$this->set_header('X-HTTP-Method-Override', 'HEAD');
	}
}

