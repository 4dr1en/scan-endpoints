<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class Validtarget implements ValidationRule, DataAwareRule
{
    private array $data = [];

    /**
     * Run the validation rule.
     *mixed
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $protocol = '';
        if(!$this->data['protocol']){
            $protocol = 'http://';
        }
        elseif($this->data['protocol'] === 'https' || $this->data['protocol'] === 'http'){
            $protocol = $this->data['protocol'] . '://';
        }
        else{
            $fail('The protocol must be either http or https.');
            return;
        }

        $port = '';
        if($this->data['port']){
            $port = ':' . $this->data['port'];
        }

         $url = $protocol . $this->data['path'] . $port;

        if(!filter_var($url, FILTER_VALIDATE_URL)){
            $fail('1-The ' . $attribute . ' must be a valid URL.');
            return;
        }

        $parsedUrl = parse_url($url);

        if(isset($parsedUrl['query']) || isset($parsedUrl['fragment'])){
            $fail('The ' . $attribute . ' must not contain a query or fragment.');
            return;
        }

        // Check if domain exists
        $dns = @dns_get_record($parsedUrl['host'], DNS_A);

        if(!$dns){
            $fail('The ' . $attribute . ' must exist and be reachable.');
        }

        // Check if port is open
        $connection = @fsockopen($parsedUrl['host'] , (int) $port ?: 80);
        if(!$connection){
            $fail('The port ' . $port . ' must be open.');
            return;
        }

        fclose($connection);

        // Check if path exists
        $headers = @get_headers($url);
        if(!$headers||
            substr($headers[0], 9, 1) === '4' ||
            substr($headers[0], 9, 1) === '5'
        ){
            $fail('The ' . $attribute . ' must exist and be reachable (error '. substr($headers[0], 9, 3).').');
            return;
        }
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
