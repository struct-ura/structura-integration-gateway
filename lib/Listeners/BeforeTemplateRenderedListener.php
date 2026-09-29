<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\StructuraIntegrationGateway\Listeners;

use OC\Security\CSP\ContentSecurityPolicyNonceManager;
use OCA\StructuraIntegrationGateway\AppInfo\Application;
use OCP\AppFramework\Http\Events\BeforeTemplateRenderedEvent;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IAppConfig;
use OCP\IURLGenerator;
use OCP\IUserSession;
use OCP\Util;

/**
 * Listener to add the configured domain to the Content Security Policy to allow loading JS from there.
 *
 * @template-implements IEventListener<BeforeTemplateRenderedEvent>
 */
class BeforeTemplateRenderedListener implements IEventListener {

	public function __construct(
		private readonly IAppConfig $appConfig,
		private readonly IURLGenerator $urlGenerator,
		private readonly IUserSession $userSession,
		private readonly ContentSecurityPolicyNonceManager $contentSecurityPolicyNonceManager,
	) {
	}

	public function handle(Event $event): void {
		if ($this->userSession->getUser() === null) {
			return;
		}

		if (!$event instanceof BeforeTemplateRenderedEvent) {

			return;
		}

		$snippet = $this->appConfig->getValueString(Application::APP_ID, 'snippet', '');
		if ($snippet === '') {
			return;
		}

		$linkToJs = $this->urlGenerator->linkToRoute('structura_integration_gateway.JS.script', [
			'v' => $this->appConfig->getValueString(Application::APP_ID, 'cachebuster', '0'),
		]);

		Util::addHeader(
			'script',
			[
				'src' => $linkToJs,
				'nonce' => $this->contentSecurityPolicyNonceManager->getNonce()
			], ''
		);
	}
}
