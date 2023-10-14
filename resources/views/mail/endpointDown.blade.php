<div>
    <p>
        {{ 
            __('Hello :username, One of your endpoint is down, We have detected that your endpoint :enpointIdentifier seem to be down. you may need to fix a bug, restart your server or fix availability issues.', [
                'username' => $details['username'],
                'enpointIdentifier' => $details['enpointIdentifier'],
            ])
        }}
    </p>
</div>
