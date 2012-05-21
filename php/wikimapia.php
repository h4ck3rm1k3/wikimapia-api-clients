<?php

/**
 * Wikimapia API Client Class
 * @author      Wikimapia API Stuff
 * @version     0.1
 * @tutorial    http://wikimapia.org/api/
 */
class WikimapiaAPI
{

    /**
     * Wikimapia API base URL
     *
     * Do not change this!
     */
    protected $url = "http://api.wikimapia.org";

    /**
     * This is your Wikimapia API Key.
     *
     * If you do not have the key, you can not use the API.
     * You can get it for free by registering on wikimapia.org,
     * and easily create a key on this page:
     * http://wikimapia.org/api/
     */
    protected $key;

    /**
     * Wikimapia Output format
     *
     * Available values: xml (default), kml, json, jsonp, binary
     */
    protected $format = "xml";

    /**
     * Packing output data parameter
     *
     * Available values: none (default), gzip
     */
    protected $packing = "none";

    /**
     * Object constructor
     * @param string $apiKey You wikimapia API key
     * @param string $format Output format
     */
    public function __construct($apiKey, $format = "xml")
    {
        $this->key = $apiKey;
        $this->format = $format;
    }

    /**
     * Set API output data format
     * @tutorial http://wikimapia.org/api
     * @param   string $format
     * @return  boolean
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return true;
    }

    /**
     * Get selected output data format
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set API output data packing
     * @tutorial http://wikimapia.org/api
     * @param   string $packing
     * @return  boolean
     */
    public function setPacking($packing)
    {
        $this->packing = $packing;
        return true;
    }

    /**
     * Get selected packing
     * @return string
     */
    public function getPacking()
    {
        return $this->packing;
    }

    /**
     * Function to get Object data by its ID
     * @param int       $objectId   Object identifier
     * @return string   Output data in selected format (see setFormat())
     */
    public function getObjectById($objectId)
    {
        return $this->doSendApiRequest("object", "id={$objectId}");
    }

    /**
     * This is a synonym of getObjectsInBoxByLatLon
     * @param int $lon_min
     * @param int $lat_min
     * @param int $lon_max
     * @param int $lat_max
     * @return string
     */
    public function getObjectInBox($lon_min, $lat_min, $lon_max, $lat_max)
    {
        return $this->getObjectsInBoxByLatLon($lon_min, $lat_min, $lon_max, $lat_max);
    }

    /**
     * Get objects in box by latitude and longitude
     * @param float $lon_min Minimum longitude
     * @param float $lat_min Minimum latitude
     * @param float $lon_max Maximum longitude
     * @param float $lat_max Maximum latitude
     * @return string
     */
    public function getObjectsInBoxByLatLon($lon_min, $lat_min, $lon_max, $lat_max)
    {
        return $this->doSendApiRequest("box", "lon_min={$lon_min}&lat_min={$lat_min}&lon_max={$lon_max}&lat_max={$lat_max}");
    }

    /**
     * Get objects in box by latitude and longitude separated by comma
     * @param string $bbox -> $lon_min,$lat_min,$lon_max,$lat_max
     * @return string
     */
    public function getObjectsInBoxByBBox($bbox)
    {
        return $this->doSendApiRequest("box", "bbox={$bbox}");
    }

    /**
     * Get objects in box by tile coordinates
     * @param int $x
     * @param int $y
     * @param int $z
     * @return string
     */
    public function getObjectsInBoxByTile($x, $y, $z)
    {
        return $this->doSendApiRequest("box", "x={$x}&y={$y}&z={$z}");
    }

    /**
     * Get objects that intersects with point
     * @param float $x
     * @param float $y
     * @return string
     */
    public function getObjectsInPoint($x, $y)
    {
        return $this->doSendApiRequest("point", "x={$x}&y={$y}");
    }

    /**
     * Get objects that determines search query
     * @param string $query
     * @return string
     */
    public function getObjectsBySearchQuery($query)
    {
        return $this->doSendApiRequest("search", "q={$query}");
    }

    /**
     * Send request to api
     * @param string $function
     * @param string $args
     * @return string
     */
    public function doSendApiRequest($function, $args)
    {
        // if you don't have a key, create it on http://wikimapia.org/api/
        if ($this->key == null) {
            return null;
        }

        // combine request URL
        $url = "{$this->url}/?function={$function}&key={$this->key}&format={$this->format}&pack={$this->packing}&{$args}";

        // making api request
        $data = file_get_contents($url);

        if (!$data) {
            throw new ErrorException("[Wikimapia API] Error: cannot open wikimapia API page. Check your connection.");
        }

        return $data;
    }
}
