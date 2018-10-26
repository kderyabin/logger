<?php
/**
 * Created on 2017-12-04
 */

namespace Kod;

use Kod\Utils\Date;

/**
 * Class Message
 * @package Log\dataFormat
 */
class Message
{
    /**
     * Preset for data to log.
     * fields with NULL values are not logged
     * @var array
     */
    protected $fields = [
        'message' => '',
        'level' => '',
        'level_code' => '',
        'datetime' => '',
    ];

    /**
     * Date/Datetime/Time fields with corresponding formats
     * @var array
     */
    protected $dates = [
        'datetime' => 'Y-m-d\TH:i:s.v\Z',
    ];

    /**
     * Function
     * @var array
     */
    protected $setters = [];
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * DefaultFormat constructor.
     * @param $config
     */
    public function __construct($config = [])
    {
        $this->init($config);
        unset($config);
    }

    /**
     * @param $config
     */
    protected function init($config)
    {
        if (!empty($config['fields'])) {
            $this->fields = array_merge($this->fields, $config['fields']);
        }
        if (!empty($config['setters'])) {
            $this->setters = $config['setters'];
        }
        if (!empty($config['filters'])) {
            $this->filters = $config['filters'];
        }
        if (!empty($config['dates'])) {
            $this->dates = array_merge($this->dates, $config['dates']);
        }
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }


    /**
     * @param int $code
     * @param string $level
     * @param string $message
     * @param array $context
     * @return array
     */
    protected function getData($code, $level, $message, $context = [])
    {
        return array_merge($this->fields, $context, ['message' => $message, 'level' => $level, 'level_code' => $code]);
    }

    /**
     * @param int $code
     * @param string $level
     * @param string $message
     * @param array $context
     * @return array
     */
    public function process($code, $level, $message, $context = [])
    {
        $data = $this->getData($code, $level, $message, $context);
        // run setters
        if ($this->setters) {
            $data = $this->applySetters($data);
        }
        //
        if ($this->dates) {
            $data = $this->setDateFields($data);
        }
        // run filters
        if ($this->filters) {
            $data = $this->applyFilters($data);
        }

        return $data;
    }
    /**
     * @param array $fields
     * @return array
     */
    public function applySetters(array $fields)
    {
        foreach ($fields as $key => $value) {
            if (isset($this->setters[$key])) {
                $fields[$key] = call_user_func($this->setters[$key], $value);
            }
        }

        return $fields;
    }
    /**
     * @param array $fields
     * @return array
     */
    public function applyFilters(array $fields)
    {
        foreach ($this->filters as $filterFunc) {
            $fields = call_user_func($filterFunc, $fields);
        }

        return $fields;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function setDateFields($data)
    {
        foreach ($this->dates as $field => $format) {
            if (empty($data[$field]) && !empty($format)) {
                $data[$field] = Date::getNow($format);
            }
        }

        return $data;
    }
}
