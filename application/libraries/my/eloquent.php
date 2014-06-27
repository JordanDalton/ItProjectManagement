<?php

use Laravel\Messages;

/**
 * Extends Eloquent Class
 *
 * @author Jordan Dalton <jordandalton@wrsgroup.com>
 */
abstract class MY_Eloquent extends Eloquent
{
    /**
     * Validation Errors
     * 
     * @var array
     */
    private $validation_errors;
        
    /**
     * Validation Messages
     * 
     * @var array
     */
    public $validation_messages = array();
    
    /**
     * Validation Rules
     * 
     * @var array
     */
    public $validation_rules = array();
    
    //--------------------------------------------------------------------------

  public function __construct($attributes = array(), $exists = false)
  {
    // initialize empty messages object
    //$this->errors = new Messages();
    parent::__construct($attributes, $exists);
  }

    //--------------------------------------------------------------------------
    
    /**
     * Validate form submission.
     * 
     * @param Input $input
     * @param type $validation_rules 
     */
    public function validate($input, $validation_key)
    {        
        // Check if validation rules do not exist.
        if( !isSet($this->validation_rules[$validation_key]) )
        {
            // Throw exception if they don't
            throw new Exception('Validation rules key \''. $validation_key . '\' is not set.');
        }

        // Check form inputs against validation
        $validation = isSet( $this->validation_messages[$validation_key] ) 
                   ? Validator::make(
                       $input, 
                       $this->validation_rules[$validation_key],
                       $this->validation_messages[$validation_key]
                   )
                   : Validator::make(
                       $input, 
                       $this->validation_rules[$validation_key]
                   );
                
        // Check if validation failed.
        if( $validation->fails() )
        {
            // Obtain the validation errors
            $this->validation_errors = $validation->errors;
            
            // Return that validation failed.
            return FALSE;
        }
        
        // Passed validation
        return TRUE;
    }
    
    //--------------------------------------------------------------------------
    
    /**
     * Get the $validation_errors
     */
    public function validation_errors()
    {
        return $this->validation_errors;
    }
    
    //--------------------------------------------------------------------------
}
/* End of file Eloquent.php */