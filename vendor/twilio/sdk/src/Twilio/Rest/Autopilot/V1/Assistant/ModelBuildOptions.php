<?php
/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Autopilot
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Twilio\Rest\Autopilot\V1\Assistant;

use Twilio\Options;
use Twilio\Values;

abstract class ModelBuildOptions
{
    /**
     * @param string $statusCallback The URL we should call using a POST method to send status information to your application.
     * @param string $uniqueName An application-defined string that uniquely identifies the new resource. This value must be a unique string of no more than 64 characters. It can be used as an alternative to the `sid` in the URL path to address the resource.
     * @return CreateModelBuildOptions Options builder
     */
    public static function create(
        
        string $statusCallback = Values::NONE,
        string $uniqueName = Values::NONE

    ): CreateModelBuildOptions
    {
        return new CreateModelBuildOptions(
            $statusCallback,
            $uniqueName
        );
    }




    /**
     * @param string $uniqueName An application-defined string that uniquely identifies the resource. This value must be a unique string of no more than 64 characters. It can be used as an alternative to the `sid` in the URL path to address the resource.
     * @return UpdateModelBuildOptions Options builder
     */
    public static function update(
        
        string $uniqueName = Values::NONE

    ): UpdateModelBuildOptions
    {
        return new UpdateModelBuildOptions(
            $uniqueName
        );
    }

}

class CreateModelBuildOptions extends Options
    {
    /**
     * @param string $statusCallback The URL we should call using a POST method to send status information to your application.
     * @param string $uniqueName An application-defined string that uniquely identifies the new resource. This value must be a unique string of no more than 64 characters. It can be used as an alternative to the `sid` in the URL path to address the resource.
     */
    public function __construct(
        
        string $statusCallback = Values::NONE,
        string $uniqueName = Values::NONE

    ) {
        $this->options['statusCallback'] = $statusCallback;
        $this->options['uniqueName'] = $uniqueName;
    }

    /**
     * The URL we should call using a POST method to send status information to your application.
     *
     * @param string $statusCallback The URL we should call using a POST method to send status information to your application.
     * @return $this Fluent Builder
     */
    public function setStatusCallback(string $statusCallback): self
    {
        $this->options['statusCallback'] = $statusCallback;
        return $this;
    }

    /**
     * An application-defined string that uniquely identifies the new resource. This value must be a unique string of no more than 64 characters. It can be used as an alternative to the `sid` in the URL path to address the resource.
     *
     * @param string $uniqueName An application-defined string that uniquely identifies the new resource. This value must be a unique string of no more than 64 characters. It can be used as an alternative to the `sid` in the URL path to address the resource.
     * @return $this Fluent Builder
     */
    public function setUniqueName(string $uniqueName): self
    {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Autopilot.V1.CreateModelBuildOptions ' . $options . ']';
    }
}




class UpdateModelBuildOptions extends Options
    {
    /**
     * @param string $uniqueName An application-defined string that uniquely identifies the resource. This value must be a unique string of no more than 64 characters. It can be used as an alternative to the `sid` in the URL path to address the resource.
     */
    public function __construct(
        
        string $uniqueName = Values::NONE

    ) {
        $this->options['uniqueName'] = $uniqueName;
    }

    /**
     * An application-defined string that uniquely identifies the resource. This value must be a unique string of no more than 64 characters. It can be used as an alternative to the `sid` in the URL path to address the resource.
     *
     * @param string $uniqueName An application-defined string that uniquely identifies the resource. This value must be a unique string of no more than 64 characters. It can be used as an alternative to the `sid` in the URL path to address the resource.
     * @return $this Fluent Builder
     */
    public function setUniqueName(string $uniqueName): self
    {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Autopilot.V1.UpdateModelBuildOptions ' . $options . ']';
    }
}

