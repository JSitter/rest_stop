<?php

/**
 * Rest Stop function to save endpoints when $endpoints 
 * is an associative array.
 */
function _rest_stop_save_endpoints($endpoints) {
    $cleaned_endpoints = array();
    foreach(array_keys($endpoints) as $url) {
      $cleaned_endpoints[_rest_stop_encode_url($url)] = $endpoints[$url];
    }
    state_set('rest stop endpoints', json_encode($cleaned_endpoints, 4));
  }

/**
 * Build test routes
 */
function _test_route_builder(&$endpoint_array){

    // Test Routes return JSON for individual nodes
    $endpoint_array['api/json/%/%'] = array(
      'page callback' => 'rest_stop_type',
      'access callback' => TRUE,
      'page arguments' => array(2, 3),
    );
  
    // Test Route
    $endpoint_array['api/json/alias/%'] = array(
        'page callback' => 'rest_stop_by_alias',
        'access callback' => TRUE,
        'page arguments' => array(3),
  
    );
  
    _rest_stop_save_endpoints($endpoint_array);
  }
  
  /**
   * Rest stop function to build endpoints from user settings
   * @param &$endpoint_array (array)
   */
  function rest_stop_endpoint_builder(&$endpoint_array) {
    // _test_route_builder($endpoint_array);
    // $ep = _rest_stop_read_endpoints();
    // $ep = array();
    // _test_route_builder($ep);
    $ep = _rest_stop_read_endpoints();
    foreach( array_keys($ep) as $index) {
      $endpoint_array[$index] = $ep[$index];
    }
  
  }
  
  /**
   * Helper functions to handle url keys
   */
  function _rest_stop_encode_url($url) {
    
    return str_replace(
      ['+', '/', '='],
      ['<%-%>', '<%_%>', '<%--%>'],
      base64_encode($url)
    );
  }
  
  function _rest_stop_decode_url($text) {
    return base64_decode(str_replace(
      ['<%-%>', '<%_%>', '<%--%>'],
      ['+', '/', '='],
      $text
    ));
  }
  
  /** 
   * Rest stop helper function to read endpoints.
   * returns array
   */
  function _rest_stop_read_endpoints() {
    $ep = state_get('rest stop endpoints');
    $decoded = json_decode($ep, True);
    $endpoints = array();
    foreach(array_keys($decoded) as $key) {
        $endpoints[_rest_stop_decode_url($key)] = $decoded[$key];

    }

    return $endpoints;
  }
  
  /**
   * Helper function to view endpoints as string
   */
  function _rest_stop_read_endpoints_string() {
    $ep = state_get('rest stop endpoints');
    $json_ep = json_decode($ep, True);
    $map_from = array();
    $map_to = array();
    foreach(array_keys($json_ep) as $key) {
        array_push($map_from, $key);
        array_push($map_to, _rest_stop_decode_url($key));
        
    }
    $decoded = str_replace($map_from, $map_to, $ep);

    return $decoded;
  }
  
  /**
   * Helper function to update endpoint string
   */
  function _rest_stop_update_endpoint_from_string($text) {
    $encoded = _rest_stop_base64_encode($text);
    state_set('rest stop endpoints', $encoded);
  }
  
/**
 * Rest stop helper function to load endpoints.
 */
// function _rest_stop_load_endpoints() {
//   $current_points = state_get('rest stop endpoints');
//   $current_points = $current_points ? $current_points : "";

//   $current_points = base64_decode($current_points);
//   dpm($current_points);
//   return json_decode($current_points);
// }
