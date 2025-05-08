namespace Priyam\ONDCAdaptor\Helper;

use Magento\Framework\HTTP\Client\Curl;

class Data
{
    protected $curl;

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    public function sendSearchRequest($data)
    {
        $url = "http://localhost:3000/search";
        $this->curl->post($url, json_encode($data));
        return $this->curl->getBody();
    }
}
