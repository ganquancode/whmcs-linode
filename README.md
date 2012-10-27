# Install

Requires PEAR and the Linode API wrapper
<https://github.com/krmdrms/linode/>

Copy linode.php to WHMCSROOT/modules/servers/linode/

Add to WHMCSROOT/configuration.php

```
$linode_api_key = '1234567890';
```

Where '1234567890' is your Linode API key