<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\StructuraIntegrationGateway\Listeners;

use OCA\StructuraIntegrationGateway\AppInfo\Application;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IUserSession;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

/**
 * Listener to add the configured domain to the Content Security Policy to allow loading JS from there.
 *
 * @template-implements IEventListener<AddContentSecurityPolicyEvent>
 */
class AddContentSecurityPolicyListener implements IEventListener {

	public function __construct(
		private readonly \OCP\IAppConfig $appConfig,
		private readonly IUserSession $userSession,
	) {
	}

	public function handle(Event $event): void {
		if (!$event instanceof AddContentSecurityPolicyEvent) {
			return;
		}

		if ($this->userSession->getUser() === null) {
			return;
		}

		// whitelist the URL to allow loading JS from this external domain
		$url = $this->appConfig->getValueString(Application::APP_ID, 'url');
		if ($url !== '') {
			$policy = new ContentSecurityPolicy();
			$policy->addAllowedScriptDomain($url);
			$policy->addAllowedImageDomain($url);
			$policy->addAllowedConnectDomain($url);
			$event->addPolicy($policy);
		}
	}
}
