<?php

namespace eYaf;

/**
 * Filter user input data.
 *
 * Filter quotes and double quotes from  user input data by applying
 * htmlspecioalchars function with parametes ENT_QUOTES and UTF-8.
 *
 * @return array the user data filtered,
 */
class Request extends \Yaf\Request\Http
{
    private $_posts;
    private $_params;
    private $_query;

    /**
     * 获取POST数据
     * 将POST数据进行转换成UTF-8
     * 存储POST到变量_posts中
     * @return mixed
     */
    public function getPost()
    {
        if ($this->_posts) {
            return $this->_posts;
        }

        $this->_posts = $this->filter_params(parent::getPost());
        return $this->_posts;
    }

    /**
     * 获取Params数据
     * 将Params数据进行转换成UTF-8
     * 存储Params到变量_params中
     * @return mixed
     */
    public function getParams()
    {
        if ($this->_params) {
            return $this->_params;
        }

        $this->_params = $this->filter_params(parent::getParams());
        return $this->_params;

    }

    /**
     * 获取Query数据
     * 将Query数据进行转换成UTF-8
     * 存储Query到变量_query中
     * @return mixed
     * @return mixed
     */
    public function getQuery()
    {
        if ($this->_query) {
            return $this->_query;
        }

        $this->_query = $this->filter_params(parent::getQuery());
        return $this->_query;

    }

    private function filter_params($params)
    {
        if (!empty($params)) {
            array_walk_recursive($params, function (&$value, $key) {
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            });
        }

        return $params;
    }
}
