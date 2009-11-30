<?php
/**
* FluentDOMSelectorCssStatusStringSingle checks for tokens in a single quoted string.
*
* @version $Id: Iterator.php 345 2009-10-19 19:51:37Z subjective $
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @copyright Copyright (c) 2009 Bastian Feder, Thomas Weinert
*
* @package FluentDOM
* @subpackage Selector
*/

/**
* FluentDOMSelectorCssStatusStringSingle checks for tokens in a single quoted string.
*
* @package FluentDOM
* @subpackage Selector
*/
class FluentDOMSelectorCssStatusStringSingle implements FluentDOMSelectorStatus {

  public function getToken($buffer, $offset) {
    if ("'" === substr($buffer, $offset, 1)) {
      return new FluentDOMSelectorCssToken(
        FluentDOMSelectorCSSToken::TOKEN_SINGLEQUOTE_STRING_END, "'", $offset
      );
    } else {
      $tokenString = substr($buffer, $offset, 2);
      if ("\\'" == $tokenString ||
          '\\\\' == $tokenString) {
        return new FluentDOMSelectorCssToken(
           FluentDOMSelectorCSSToken::TOKEN_STRING_ESCAPED_CHAR, $tokenString, $offset
        );
      } else {
        $tokenString = FluentDOMSelectorScanner::matchPattern(
          $buffer, $offset, '([^\\\\\']+)'
        );
        if (!empty($tokenString)) {
          return new FluentDOMSelectorCssToken(
            FluentDOMSelectorCSSToken::TOKEN_STRING_CHARS, $tokenString, $offset
          );
        }
      }
    }
    return NULL;
  }

  public function isEndToken($token) {
    return (
      $token->type == FluentDOMSelectorCssToken::TOKEN_SINGLEQUOTE_STRING_END
    );
  }

  public function getNewStatus($token) {
    return NULL;
  }
}