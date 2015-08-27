<?php namespace amekusa\WPVagrantize;

class AjaxAction {
	private $name;
	private $callback;
	private $nonce;
	private $isNoPriv;
	private $isRegistered;

	public function __construct($xName, $xCallback, $xIsNoPriv = false) {
		$this->name = $xName;
		$this->callback = $xCallback;
		$this->isNoPriv = $xIsNoPriv;
		$this->nonce = wp_create_nonce($this->name);
	}

	public function register($xPriority = 10) {
		if ($this->isRegistered) throw new \RuntimeException('The AjaxAction is already registered');
		$action = function () {
			$this->preAction();
			call_user_func($this->callback);
		};
		add_action('wp_ajax_' . $this->name, $action, $xPriority);
		if ($this->isNoPriv) add_action('wp_ajax_nopriv_' . $this->name, $action, $xPriority);
		$this->isRegistered = true;
	}

	private function preAction() {
		if (!check_ajax_referer($this->name, 'nonce', false)) {
			header('HTTP/1.1 500 Internal Server Error');
			die();
		}
		if (!defined('AJAX_ACTION')) define('AJAX_ACTION', $this->name);
	}

	public final function getName() {
		return $this->name;
	}

	public final function getNonce() {
		return $this->nonce;
	}

	public function forJQAjax() {
		return array (
			'url' => admin_url('admin-ajax.php'),
			'method' => 'POST', // jQuery >= 1.9.0
			'type' => 'POST',   // jQuery <  1.9.0
			'data' => array (
				'action' => $this->getName(),
				'nonce' => $this->getNonce()
			),
			'dataType' => 'json',
			'cache' => false
		);
	}
}
