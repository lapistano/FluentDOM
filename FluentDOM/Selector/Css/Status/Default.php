<?php
/**
* FluentDOMSelectorCssStatusDefault checks for tokens css selector string.
*
* @version $Id: Iterator.php 345 2009-10-19 19:51:37Z subjective $
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @copyright Copyright (c) 2009 Bastian Feder, Thomas Weinert
*
* @package FluentDOM
* @subpackage Selector
*/

/**
* FluentDOMSelectorCssStatusDefault checks for tokens css selector string.
*
* @package FluentDOM
* @subpackage Selector
*/
class FluentDOMSelectorCssStatusDefault implements FluentDOMSelectorStatus {

  /**
  * single char tokens
  * @var array
  */
  protected $_tokenChars = array(
    FluentDOMSelectorCssToken::TOKEN_ATTRIBUTES_SELECTOR_START => '[',
    FluentDOMSelectorCssToken::TOKEN_PARAMETERS_START => '(',
    FluentDOMSelectorCssToken::TOKEN_PARAMETERS_END => ')',
    FluentDOMSelectorCssToken::TOKEN_SINGLEQUOTE_STRING_START => "'",
    FluentDOMSelectorCssToken::TOKEN_DOUBLEQUOTE_STRING_START => '"'
  );

  /**
  * patterns for more complex tokens
  * @var array
  */
  protected $_tokenPatterns = array(
    FluentDOMSelectorCssToken::TOKEN_WHITESPACE => '([\r\n\t ]+)',
    FluentDOMSelectorCssToken::TOKEN_SELECTOR_SEPARATOR => '([>+~])',
    FluentDOMSelectorCssToken::TOKEN_TYPE_SELECTOR => '([^\r\n\t .,#:()[\\]\\\'"]+)',
    FluentDOMSelectorCssToken::TOKEN_CLASS_SELECTOR => '(\.[^\r\n\t .,#:()[\\]\\\'"]+)',
    FluentDOMSelectorCssToken::TOKEN_ID_SELECTOR => '(#[^\r\n\t .,#:()[\\]\\\'"]+)',
    FluentDOMSelectorCssToken::TOKEN_PSEUDO_CLASS => '(:[^\r\n\t .,#:()[\\]\\\'"]+)'
  );

  public function getToken($buffer, $offset) {
    $char = substr($buffer, $offset, 1);
    foreach ($this->_tokenChars as $type => $expectedChar) {
      if ($char === $expectedChar) {
        return new FluentDOMSelectorCssToken(
          $type, $char, $offset
        );
      }
    }
    foreach ($this->_tokenPatterns as $type => $pattern) {
      $tokenString = FluentDOMSelectorScanner::matchPattern(
        $buffer, $offset, $pattern
      );
      if (!empty($tokenString)) {
        return new FluentDOMSelectorCssToken(
          $type, $tokenString, $offset
        );
      }
    }
    return NULL;
  }

  public function isEndToken($token) {
    return FALSE;
  }

  public function getNewStatus($token) {
    switch ($token->type) {
    case FluentDOMSelectorCssToken::TOKEN_SINGLEQUOTE_STRING_START :
      return new FluentDOMSelectorCssStatusStringSingle();
    case FluentDOMSelectorCssToken::TOKEN_DOUBLEQUOTE_STRING_START :
      return new FluentDOMSelectorCssStatusStringDouble();
    case FluentDOMSelectorCssToken::TOKEN_ATTRIBUTES_SELECTOR_START :
      return new FluentDOMSelectorCssStatusAttributes();
    }
    return NULL;
  }
}