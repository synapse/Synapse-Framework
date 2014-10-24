<?php

/**
 * @package     Synapse
 * @subpackage  Form/Field
 */

defined('_INIT') or die;

class Field extends FormElement {

    public $id           = null;
    public $name         = null;
    public $label        = null;
    public $labelclass   = null;
    public $description  = null;
    public $type         = null;
    public $required     = false;
    public $default      = null;
    public $message      = null;
    public $filter       = 'string';
    public $validate     = null;
    public $attributes   = null;
    public $options      = null;
    public $template     = array();
    protected $form      = null;
    protected $value     = null;
    protected $error     = null;

    public function __construct($field = null)
    {
        if($field && (is_array($field) || is_object($field))){
            $this->load((object)$field);
        }
        return $this;
    }

    protected function load($field)
    {
        if(!is_object($field)){
            throw new Error('load expects an object, '.gettype($field).' given');
        }

        if(isset($field->id)){
            $this->setID($field->id);
        }

        if(isset($field->name)){
            $this->setName($field->name);
        }

        if(isset($field->label)){
            $this->setLabel($field->label);
        }

        if(isset($field->attributes)){
            $this->setAttributes($field->attributes);
        }

        if(isset($field->options)){
            $this->setOptions($field->options);
        }

        if(isset($field->labelclass)){
            $this->setLabelClass($field->labelclass);
        }

        if(isset($field->description)){
            $this->setDescription($field->description);
        }

        if(isset($field->required)){
            $this->setRequired($field->required);
        }

        if(isset($field->default)){
            $this->setDefault($field->default);
        }

        if(isset($field->message)){
            $this->setMessage($field->message);
        }

        if(isset($field->filter)){
            $this->setFilter($field->filter);
        }

        if(isset($field->validate)){
            $this->setValidate($field->validate);
        }

        if(isset($field->template)){
            $this->setTemplate($field->template);
        }

        if(isset($field->type)){
            $this->setType($field->type);
        }
    }

    /**
     * Sets the ID of the field
     * @param String $id
     * @return $this
     */
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the name of the field
     * @param String $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the label of the field
     * @param String $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Sets the attributes of the field
     * @param Array|Object $attributes
     * @return $this
     * @throws Error
     */
    public function setAttributes($attributes)
    {
        if(!is_array($attributes) && !is_object($attributes)){
            throw new Error('setAttributes expects an object or an array, '.gettype($attributes).' given');
        }

        $this->attributes = (object)$attributes;
        return $this;
    }

    /**
     * Returns the field attributes
     * @return Object
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the additional options for the field
     * @param Array|Object $attributes
     * @return $this
     * @throws Error
     */
    public function setOptions($options)
    {
        if(!is_array($options) && !is_object($options)){
            throw new Error('setOptions expects an object or an array, '.gettype($options).' given');
        }

        $this->options = (object)$options;
        return $this;
    }

    /**
     * Returns the field options
     * @return Object
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the fields label class
     * @param String $labelclass
     * @return $this
     */
    public function setLabelClass($labelclass)
    {
        $this->labelclass = $labelclass;
        return $this;
    }

    /**
     * Sets the field description
     * @param String $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Sets the field type
     * @param String $type
     * @return $this
     */
    public function setType($type)
    {
        $typeClass = 'FieldType'.ucfirst($type);
        $this->type = new $typeClass($this);

        if(!$this->type){
            throw new Error('Missing field type class '.$typeClass);
        }

        if(count($this->template)){
            $this->type->setTemplate($this->getTemplate());
        }

        return $this;
    }

    /**
     * Sets the required property of the field
     * @param Boolean $required
     * @return $this
     */
    public function setRequired($required)
    {
        $this->required = $required ? true : false;
        return $this;
    }

    /**
     * Sets the default value of the field
     * @param Mixed $default
     * @return $this
     */
    public function setDefault($default)
    {
        if($this->filter){
            $default = $this->clean($default, $this->filter);
        }

        $this->default = $default;
        return $this;
    }

    /**
     * Returns the default field value
     * @return Mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Sets the error message of the field
     * @param String $error
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Sets the filter type of the field
     * @param String $filter
     * @return $this
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Sets the validation type of the field
     * @param Array|Object $validate
     * @return $this
     * @throws Error
     */
    public function setValidate($validate)
    {
        if(!is_array($validate) && !is_object($validate)){
            throw new Error('setValidate expects an object or an array, '.gettype($validate).' given');
        }

        if(is_object($validate) && !property_exists($validate, 'type')){
            throw new Error('Validate must have a type property');
        }

        $this->validate = $validate;
        return $this;
    }

    /**
     * Sets the value of the field
     * @param Mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        if($this->filter){
            $value = $this->clean($value, $this->filter);
        }

        $this->value = $value;
        return $this;
    }

    /**
     * Returns the value of the field
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Validates the field value
     */
    public function validate()
    {

        // check required value
        $val = $this->getValue();
        if($this->required && empty($val)){
            if(isset($this->message) && strlen($this->message)) {
                $this->error = __($this->message);
            } else {
                $this->error = __("Field {1}: value required", $this->name);
            }
            return false;
        }

        // check field type validation
        if($this->type){
            if(!$this->type->validate()){
                if(isset($this->message) && strlen($this->message)) {
                    $this->error = __($this->message);
                } else {
                    $this->error = __("Invalid field value");
                }
                return false;
            }
        }

        // if there are not extra validations or if the value is empty
        // return OK
        if(!$this->validate || empty($val)){
            return true;
        }


        // validate using extra validators
        $validations = array();

        if(is_object($this->validate)){
            $validations[] = $this->validate;
        } else {
            $validations = $this->validate;
        }

        foreach($validations as $i=>$validate){
            $validateClass = 'FieldValidate'.ucfirst($validate->type);
            $fieldValidate = new $validateClass();

            if(!$fieldValidate){
                throw new Error('Missing field type class '.$validateClass);
            }

            if(!$fieldValidate->test($this->getValue(), $this, $validate)){
                if(isset($validate->message) && strlen($validate->message)) {
                    $this->error = __($validate->message);
                } else {
                    $this->error = __("Field not valid");
                }
                return false;
            }
        }

        /*
        $validateClass = 'FieldValidate'.ucfirst($this->validate->type);
        $fieldValidate = new $validateClass();

        if(!$fieldValidate){
            throw new Error('Missing field type class '.$validateClass);
        }

        if(!$fieldValidate->test($this->getValue(), $this, $this->validate)){
            if(isset($this->validate->message) && strlen($this->validate->message)) {
                $this->error = $this->validate->message;
            } else {
                $this->error = "Field not valid";
            }
            return false;
        }
        */

        return true;
    }

    /**
     * Returns the list of errors from the validation method
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets the html template splitted in an array
     * @param Array $template
     */
    public function setTemplate($template)
    {
        if(!is_array($template) && !is_string($template)){
            throw new Error('setTemplate expects an array or string, '.gettype($template).' given');
        }

        $this->template = $template;
        return $this;
    }

    /**
     * Sets the current reference of the form
     * @param Form $form
     * @return $this
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Returns the current form
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Loads a fieldset template from a file
     * @param String $path
     */
    public function loadTemplate($path)
    {
        if(!FS::exists($path)){
            throw new Error('Field template file not found at the given path: '.$path);
        }

        $template = array(file_get_contents($path));
        $this->setTemplate($template);
    }

    /**
     * Return the html template
     * @return string
     */
    public function getTemplate()
    {
        if(is_array($this->template)) {
            return implode("\r\n", $this->template);
        }

        return $this->template;
    }

    /**
     * Renders and echoes out the fieldset in html
     */
    public function render($return = false)
    {
        if(!$this->type){
            throw new Error('Missing field type');
        }

        $template = $this->type->render();

        if($return) return $template;

        echo $template;
    }

    public function __toString()
    {
        return $this->render(true);
    }

    /**
	 * Method to be called by another php script. Processes for XSS and
	 * specified bad code.
	 *
	 * @param   mixed   $source  Input string/array-of-string to be 'cleaned'
	 * @param   string  $type    The return type for the variable:
	 *                           INT:       An integer,
	 *                           UINT:      An unsigned integer,
	 *                           FLOAT:     A floating point number,
	 *                           BOOLEAN:   A boolean value,
	 *                           WORD:      A string containing A-Z or underscores only (not case sensitive),
	 *                           ALNUM:     A string containing A-Z or 0-9 only (not case sensitive),
	 *                           CMD:       A string containing A-Z, 0-9, underscores, periods or hyphens (not case sensitive),
	 *                           BASE64:    A string containing A-Z, 0-9, forward slashes, plus or equals (not case sensitive),
	 *                           STRING:    A fully decoded and sanitised string (default),
	 *                           HTML:      A sanitised string,
	 *                           ARRAY:     An array,
	 *                           PATH:      A sanitised file path,
	 *                           USERNAME:  Do not use (use an application specific filter),
	 *                           RAW:       The raw string is returned with no filtering,
	 *                           unknown:   An unknown filter will act like STRING. If the input is an array it will return an
	 *                                      array of fully decoded and sanitised strings.
	 *
	 * @return  mixed  'Cleaned' version of input parameter
	 */
	public function clean($source, $type = 'string')
	{
		// Handle the type constraint
		switch (strtoupper($type))
		{
			case 'INT':
			case 'INTEGER':
				// Only use the first integer value
				preg_match('/-?[0-9]+/', (string) $source, $matches);
				$result = @ (int) $matches[0];
				break;

			case 'UINT':
				// Only use the first integer value
				preg_match('/-?[0-9]+/', (string) $source, $matches);
				$result = @ abs((int) $matches[0]);
				break;

			case 'FLOAT':
			case 'DOUBLE':
				// Only use the first floating point value
				preg_match('/-?[0-9]+(\.[0-9]+)?/', (string) $source, $matches);
				$result = @ (float) $matches[0];
				break;

			case 'BOOL':
			case 'BOOLEAN':
				$result = (bool) $source;
				break;

			case 'WORD':
				$result = (string) preg_replace('/[^A-Z_]/i', '', $source);
				break;

			case 'ALNUM':
				$result = (string) preg_replace('/[^A-Z0-9]/i', '', $source);
				break;

			case 'CMD':
				$result = (string) preg_replace('/[^A-Z0-9_\.-]/i', '', $source);
				$result = ltrim($result, '.');
				break;

			case 'BASE64':
				$result = (string) preg_replace('/[^A-Z0-9\/+=]/i', '', $source);
				break;

			case 'STRING':
				$result = (string) $this->_remove($this->_decode((string) $source));
				break;

			case 'HTML':
				$result = (string) $this->_remove((string) $source);
				break;

			case 'ARRAY':
				$result = (array) $source;
				break;

			case 'PATH':
				$pattern = '/^[A-Za-z0-9_-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$/';
				preg_match($pattern, (string) $source, $matches);
				$result = @ (string) $matches[0];
				break;

			case 'USERNAME':
				$result = (string) preg_replace('/[\x00-\x1F\x7F<>"\'%&]/', '', $source);
				break;

			case 'RAW':
				$result = $source;
				break;

			default:
				// Are we dealing with an array?
				if (is_array($source))
				{
					foreach ($source as $key => $value)
					{
						// Filter element for XSS and other 'bad' code etc.
						if (is_string($value))
						{
							$source[$key] = $this->_remove($this->_decode($value));
						}
					}
					$result = $source;
				}
				else
				{
					// Or a string?
					if (is_string($source) && !empty($source))
					{
						// Filter source for XSS and other 'bad' code etc.
						$result = $this->_remove($this->_decode($source));
					}
					else
					{
						// Not an array or string.. return the passed parameter
						$result = $source;
					}
				}
				break;
		}

		return $result;
	}

    /**
	 * Internal method to iteratively remove all unwanted tags and attributes
	 *
	 * @param   string  $source  Input string to be 'cleaned'
	 *
	 * @return  string  'Cleaned' version of input parameter
	 */
	protected function _remove($source)
	{
		$loopCounter = 0;

		// Iteration provides nested tag protection
		while ($source != $this->_cleanTags($source))
		{
			$source = $this->_cleanTags($source);
			$loopCounter++;
		}

		return $source;
	}

	/**
	 * Internal method to strip a string of certain tags
	 *
	 * @param   string  $source  Input string to be 'cleaned'
	 *
	 * @return  string  'Cleaned' version of input parameter
	 */
	protected function _cleanTags($source)
	{
		// First, pre-process this for illegal characters inside attribute values
		$source = $this->_escapeAttributeValues($source);

		// In the beginning we don't really have a tag, so everything is postTag
		$preTag = null;
		$postTag = $source;
		$currentSpace = false;

		// Setting to null to deal with undefined variables
		$attr = '';

		// Is there a tag? If so it will certainly start with a '<'.
		$tagOpen_start = strpos($source, '<');

		while ($tagOpen_start !== false)
		{
			// Get some information about the tag we are processing
			$preTag .= substr($postTag, 0, $tagOpen_start);
			$postTag = substr($postTag, $tagOpen_start);
			$fromTagOpen = substr($postTag, 1);
			$tagOpen_end = strpos($fromTagOpen, '>');

			// Check for mal-formed tag where we have a second '<' before the first '>'
			$nextOpenTag = (strlen($postTag) > $tagOpen_start) ? strpos($postTag, '<', $tagOpen_start + 1) : false;

			if (($nextOpenTag !== false) && ($nextOpenTag < $tagOpen_end))
			{
				// At this point we have a mal-formed tag -- remove the offending open
				$postTag = substr($postTag, 0, $tagOpen_start) . substr($postTag, $tagOpen_start + 1);
				$tagOpen_start = strpos($postTag, '<');
				continue;
			}

			// Let's catch any non-terminated tags and skip over them
			if ($tagOpen_end === false)
			{
				$postTag = substr($postTag, $tagOpen_start + 1);
				$tagOpen_start = strpos($postTag, '<');
				continue;
			}

			// Do we have a nested tag?
			$tagOpen_nested = strpos($fromTagOpen, '<');

			if (($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end))
			{
				$preTag .= substr($postTag, 0, ($tagOpen_nested + 1));
				$postTag = substr($postTag, ($tagOpen_nested + 1));
				$tagOpen_start = strpos($postTag, '<');
				continue;
			}

			// Let's get some information about our tag and setup attribute pairs
			$tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
			$currentTag = substr($fromTagOpen, 0, $tagOpen_end);
			$tagLength = strlen($currentTag);
			$tagLeft = $currentTag;
			$attrSet = array();
			$currentSpace = strpos($tagLeft, ' ');

			// Are we an open tag or a close tag?
			if (substr($currentTag, 0, 1) == '/')
			{
				// Close Tag
				$isCloseTag = true;
				list ($tagName) = explode(' ', $currentTag);
				$tagName = substr($tagName, 1);
			}
			else
			{
				// Open Tag
				$isCloseTag = false;
				list ($tagName) = explode(' ', $currentTag);
			}

			/*
			 * Exclude all "non-regular" tagnames
			 * OR no tagname
			 * OR remove if xssauto is on and tag is blacklisted
			 */
			if ((!preg_match("/^[a-z][a-z0-9]*$/i", $tagName)) || (!$tagName) || ((in_array(strtolower($tagName), $this->tagBlacklist)) && ($this->xssAuto)))
			{
				$postTag = substr($postTag, ($tagLength + 2));
				$tagOpen_start = strpos($postTag, '<');

				// Strip tag
				continue;
			}

			/*
			 * Time to grab any attributes from the tag... need this section in
			 * case attributes have spaces in the values.
			 */
			while ($currentSpace !== false)
			{
				$attr = '';
				$fromSpace = substr($tagLeft, ($currentSpace + 1));
				$nextEqual = strpos($fromSpace, '=');
				$nextSpace = strpos($fromSpace, ' ');
				$openQuotes = strpos($fromSpace, '"');
				$closeQuotes = strpos(substr($fromSpace, ($openQuotes + 1)), '"') + $openQuotes + 1;

				$startAtt = '';
				$startAttPosition = 0;

				// Find position of equal and open quotes ignoring
				if (preg_match('#\s*=\s*\"#', $fromSpace, $matches, PREG_OFFSET_CAPTURE))
				{
					$startAtt = $matches[0][0];
					$startAttPosition = $matches[0][1];
					$closeQuotes = strpos(substr($fromSpace, ($startAttPosition + strlen($startAtt))), '"') + $startAttPosition + strlen($startAtt);
					$nextEqual = $startAttPosition + strpos($startAtt, '=');
					$openQuotes = $startAttPosition + strpos($startAtt, '"');
					$nextSpace = strpos(substr($fromSpace, $closeQuotes), ' ') + $closeQuotes;
				}

				// Do we have an attribute to process? [check for equal sign]
				if ($fromSpace != '/' && (($nextEqual && $nextSpace && $nextSpace < $nextEqual) || !$nextEqual))
				{
					if (!$nextEqual)
					{
						$attribEnd = strpos($fromSpace, '/') - 1;
					}
					else
					{
						$attribEnd = $nextSpace - 1;
					}
					// If there is an ending, use this, if not, do not worry.
					if ($attribEnd > 0)
					{
						$fromSpace = substr($fromSpace, $attribEnd + 1);
					}
				}
				if (strpos($fromSpace, '=') !== false)
				{
					// If the attribute value is wrapped in quotes we need to grab the substring from
					// the closing quote, otherwise grab until the next space.
					if (($openQuotes !== false) && (strpos(substr($fromSpace, ($openQuotes + 1)), '"') !== false))
					{
						$attr = substr($fromSpace, 0, ($closeQuotes + 1));
					}
					else
					{
						$attr = substr($fromSpace, 0, $nextSpace);
					}
				}
				// No more equal signs so add any extra text in the tag into the attribute array [eg. checked]
				else
				{
					if ($fromSpace != '/')
					{
						$attr = substr($fromSpace, 0, $nextSpace);
					}
				}

				// Last Attribute Pair
				if (!$attr && $fromSpace != '/')
				{
					$attr = $fromSpace;
				}

				// Add attribute pair to the attribute array
				$attrSet[] = $attr;

				// Move search point and continue iteration
				$tagLeft = substr($fromSpace, strlen($attr));
				$currentSpace = strpos($tagLeft, ' ');
			}

			// Is our tag in the user input array?
			$tagFound = in_array(strtolower($tagName), $this->tagsArray);

			// If the tag is allowed let's append it to the output string.
			if ((!$tagFound && $this->tagsMethod) || ($tagFound && !$this->tagsMethod))
			{
				// Reconstruct tag with allowed attributes
				if (!$isCloseTag)
				{
					// Open or single tag
					$attrSet = $this->_cleanAttributes($attrSet);
					$preTag .= '<' . $tagName;

					for ($i = 0, $count = count($attrSet); $i < $count; $i++)
					{
						$preTag .= ' ' . $attrSet[$i];
					}

					// Reformat single tags to XHTML
					if (strpos($fromTagOpen, '</' . $tagName))
					{
						$preTag .= '>';
					}
					else
					{
						$preTag .= ' />';
					}
				}
				// Closing tag
				else
				{
					$preTag .= '</' . $tagName . '>';
				}
			}

			// Find next tag's start and continue iteration
			$postTag = substr($postTag, ($tagLength + 2));
			$tagOpen_start = strpos($postTag, '<');
		}

		// Append any code after the end of tags and return
		if ($postTag != '<')
		{
			$preTag .= $postTag;
		}

		return $preTag;
	}

    /**
	 * Try to convert to plaintext
	 *
	 * @param   string  $source  The source string.
	 *
	 * @return  string  Plaintext string
	 *
	 * @since   11.1
	 */
	protected function _decode($source)
	{
		static $ttr;

		if (!is_array($ttr))
		{
			// Entity decode
			$trans_tbl = get_html_translation_table(HTML_ENTITIES, ENT_COMPAT, 'ISO-8859-1');

			foreach ($trans_tbl as $k => $v)
			{
				$ttr[$v] = utf8_encode($k);
			}
		}

		$source = strtr($source, $ttr);

		// Convert decimal
		$source = preg_replace_callback('/&#(\d+);/m', function($m)
		{
			return utf8_encode(chr($m[1]));
		}, $source
		);

		// Convert hex
		$source = preg_replace_callback('/&#x([a-f0-9]+);/mi', function($m)
		{
			return utf8_encode(chr('0x' . $m[1]));
		}, $source
		);

		return $source;
	}

    /**
	 * Escape < > and " inside attribute values
	 *
	 * @param   string  $source  The source string.
	 *
	 * @return  string  Filtered string
	 *
	 * @since    11.1
	 */
	protected function _escapeAttributeValues($source)
	{
		$alreadyFiltered = '';
		$remainder = $source;
		$badChars = array('<', '"', '>');
		$escapedChars = array('&lt;', '&quot;', '&gt;');

		// Process each portion based on presence of =" and "<space>, "/>, or ">
		// See if there are any more attributes to process
		while (preg_match('#<[^>]*?=\s*?(\"|\')#s', $remainder, $matches, PREG_OFFSET_CAPTURE))
		{
			// Get the portion before the attribute value
			$quotePosition = $matches[0][1];
			$nextBefore = $quotePosition + strlen($matches[0][0]);

			// Figure out if we have a single or double quote and look for the matching closing quote
			// Closing quote should be "/>, ">, "<space>, or " at the end of the string
			$quote = substr($matches[0][0], -1);
			$pregMatch = ($quote == '"') ? '#(\"\s*/\s*>|\"\s*>|\"\s+|\"$)#' : "#(\'\s*/\s*>|\'\s*>|\'\s+|\'$)#";

			// Get the portion after attribute value
			if (preg_match($pregMatch, substr($remainder, $nextBefore), $matches, PREG_OFFSET_CAPTURE))
			{
				// We have a closing quote
				$nextAfter = $nextBefore + $matches[0][1];
			}
			else
			{
				// No closing quote
				$nextAfter = strlen($remainder);
			}

			// Get the actual attribute value
			$attributeValue = substr($remainder, $nextBefore, $nextAfter - $nextBefore);

			// Escape bad chars
			$attributeValue = str_replace($badChars, $escapedChars, $attributeValue);
			$attributeValue = $this->_stripCSSExpressions($attributeValue);
			$alreadyFiltered .= substr($remainder, 0, $nextBefore) . $attributeValue . $quote;
			$remainder = substr($remainder, $nextAfter + 1);
		}

		// At this point, we just have to return the $alreadyFiltered and the $remainder
		return $alreadyFiltered . $remainder;
	}
}