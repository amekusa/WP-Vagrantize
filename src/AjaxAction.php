<?php
namespace amekusa\WPVagrantize;

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
		check_ajax_referer($this->name);
		nocache_headers();
	}

	public final function getNonce() {
		return $this->nonce;
	}
}
