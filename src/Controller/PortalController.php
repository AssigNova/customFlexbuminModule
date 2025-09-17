<?php

namespace Drupal\custom_portal\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class PortalController extends ControllerBase
{

  public function content()
  {
    $links = [
      [
        'title' => 'I am a Patient',
        'url' => Url::fromRoute('custom_portal.patient_form'),
      ],
      [
        'title' => 'I am a Distributor',
        'url' => Url::fromRoute('custom_portal.distributor_form'),
      ],
      [
        'title' => 'I am an HCP',
        'url' => Url::fromRoute('custom_portal.hcp_form'),
      ],
    ];
    return [
      '#theme' => 'portal_options_page',
      '#links' => $links,
      '#attached' => [
        'library' => ['custom_portal/portal_styles'],
      ],
    ];
  }
}
