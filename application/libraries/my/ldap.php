<?php

/**
 *  My Ldap
 *
 *  Description goes here..
 *
 *  @author Jordan Dalton <jordandalton@wrsgroup.com>
 *  @created Sep 11, 2012, 2:09:37 PM
 */
class My_Ldap
{
    //--------------------------------------------------------------------------
    
    /**
     * Search for user by full/partial name.
     * 
     * @param string $term  The search term.
     * @return array 
     */
    public static function search( $term )
    {
        // Search LDAP and return the results.
        return Auth::driver()->search_ldap($term, Config::get('auth.ldap.basedn'));
    }
    
    //--------------------------------------------------------------------------

    /**
     * Fetch a users ldap profile via their full name.
     * 
     * @param string $full_name The users full name.
     * @return object
     */
    public static function fetchProfile( $full_name )
    {
        // Concatenate the string
        $dn = "CN={$full_name}," . Config::get('auth.ldap.basedn');
        
        $test = false;
        
        try {
            
            $test = Auth::driver()->get_user_by_dn($dn);
            
        } catch(Exception $exc) {
            
            $test = $exc;
            
        }
        
        return $test;
        return Auth::driver()->get_user_by_dn($dn);
    }
    
    //--------------------------------------------------------------------------
}
/* End of file ldap.php */