<?php

// SPDX-License-Identifier: MIT
// SPDX-FileCopyrightText: © 2021 CrowdSec <info@crowdsec.net>

namespace OPNsense\CrowdSec\Api;

use OPNsense\Base\ApiControllerBase;
use OPNsense\Core\Backend;

/**
 * Class ServiceController
 * @package OPNsense\Cron
 */
class ServiceController extends ApiControllerBase
{
    /**
     * reconfigure CrowdSec
     */
    public function reloadAction()
    {
        $status = "failed";
        if ($this->request->isPost()) {
            $backend = new Backend();
            $bckresult = trim($backend->configdRun('template reload OPNsense/CrowdSec'));
            if ($bckresult == "OK") {
                $bckresult = trim($backend->configdRun('crowdsec reconfigure'));
                if ($bckresult == "OK") {
                    $status = "ok";
                }
            }
        }
        return array("status" => $status);
    }

    /**
     * retrieve status of crowdsec
     * @return array
     * @throws \Exception
     */
    public function statusAction()
    {
        $backend = new Backend();
        $response = $backend->configdRun("crowdsec crowdsec-status");

        if (strpos($response, "not running") > 0) {
            $status = "stopped";
        } elseif (strpos($response, "is running") > 0) {
            $status = "running";
        } else {
            $status = "unkown";
        };

        $response = $backend->configdRun("crowdsec crowdsec-firewall-status");

        if (strpos($response, "not running") > 0) {
            $firewall_status = "stopped";
        } elseif (strpos($response, "is running") > 0) {
            $firewall_status = "running";
        } else {
            $firewall_status = "unknown";
        };

        return array(
            "crowdsec-status" => $status,
            "crowdsec-firewall-status" => $firewall_status,
        );
    }

    /**
     * return debug information
     * @return array
     */
    public function debugAction()
    {
        $backend = new Backend();
        $response = $backend->configdRun("crowdsec debug");
        return array("message" => $response);
    }
}
