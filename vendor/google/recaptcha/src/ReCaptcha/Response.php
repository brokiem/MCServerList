<?php
/**
 * This is a PHP library that handles calling reCAPTCHA.
 *
 * BSD 3-Clause License
 * @copyright (c) 2019, Google Inc.
 * @link https://www.google.com/recaptcha
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright notice, this
 *    list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the copyright holder nor the names of its
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace ReCaptcha;

/**
 * The response returned from the service.
 */
class Response {
    /**
     * Success or failure.
     * @var boolean
     */
    private bool $success;

    /**
     * Error code strings.
     * @var array
     */
    private array $errorCodes;

    /**
     * The hostname of the site where the reCAPTCHA was solved.
     * @var string|null
     */
    private ?string $hostname;

    /**
     * Timestamp of the challenge load (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)
     * @var string|null
     */
    private ?string $challengeTs;

    /**
     * APK package name
     * @var string|null
     */
    private ?string $apkPackageName;

    /**
     * Score assigned to the request
     * @var float|null
     */
    private ?float $score;

    /**
     * Action as specified by the page
     * @var string|null
     */
    private ?string $action;

    /**
     * Constructor.
     *
     * @param boolean $success
     * @param string|null $hostname
     * @param string|null $challengeTs
     * @param string|null $apkPackageName
     * @param float|null $score
     * @param string|null $action
     * @param array $errorCodes
     */
    public function __construct(bool $success, array $errorCodes = [], string $hostname = null, string $challengeTs = null, string $apkPackageName = null, float $score = null, string $action = null) {
        $this->success = $success;
        $this->hostname = $hostname;
        $this->challengeTs = $challengeTs;
        $this->apkPackageName = $apkPackageName;
        $this->score = $score;
        $this->action = $action;
        $this->errorCodes = $errorCodes;
    }

    /**
     * Build the response from the expected JSON returned by the service.
     *
     * @param string $json
     * @return \ReCaptcha\Response
     */
    public static function fromJson(string $json): Response {
        $responseData = json_decode($json, true);

        if (!$responseData) {
            return new Response(false, array(ReCaptcha::E_INVALID_JSON));
        }

        $hostname = $responseData['hostname'] ?? null;
        $challengeTs = $responseData['challenge_ts'] ?? null;
        $apkPackageName = $responseData['apk_package_name'] ?? null;
        $score = isset($responseData['score']) ? (float)$responseData['score'] : null;
        $action = $responseData['action'] ?? null;

        if (isset($responseData['success']) && $responseData['success'] == true) {
            return new Response(true, array(), $hostname, $challengeTs, $apkPackageName, $score, $action);
        }

        if (isset($responseData['error-codes']) && is_array($responseData['error-codes'])) {
            return new Response(false, $responseData['error-codes'], $hostname, $challengeTs, $apkPackageName, $score, $action);
        }

        return new Response(false, array(ReCaptcha::E_UNKNOWN_ERROR), $hostname, $challengeTs, $apkPackageName, $score, $action);
    }

    public function toArray(): array {
        return array(
            'success' => $this->isSuccess(),
            'hostname' => $this->getHostname(),
            'challenge_ts' => $this->getChallengeTs(),
            'apk_package_name' => $this->getApkPackageName(),
            'score' => $this->getScore(),
            'action' => $this->getAction(),
            'error-codes' => $this->getErrorCodes(),
        );
    }

    /**
     * Is success?
     *
     * @return boolean
     */
    public function isSuccess(): bool {
        return $this->success;
    }

    /**
     * Get hostname.
     *
     * @return string|null
     */
    public function getHostname(): ?string {
        return $this->hostname;
    }

    /**
     * Get challenge timestamp
     *
     * @return string|null
     */
    public function getChallengeTs(): ?string {
        return $this->challengeTs;
    }

    /**
     * Get APK package name
     *
     * @return string|null
     */
    public function getApkPackageName(): ?string {
        return $this->apkPackageName;
    }

    /**
     * Get score
     *
     * @return float|null
     */
    public function getScore(): ?float {
        return $this->score;
    }

    /**
     * Get action
     *
     * @return string|null
     */
    public function getAction(): ?string {
        return $this->action;
    }

    /**
     * Get error codes.
     *
     * @return array
     */
    public function getErrorCodes(): array {
        return $this->errorCodes;
    }
}
