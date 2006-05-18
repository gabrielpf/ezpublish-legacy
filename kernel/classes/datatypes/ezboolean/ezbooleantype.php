<?php
//
// Definition of eZBooleanType class
//
// Created on: <27-Jun-2002 18:24:54 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*!
  \class eZBooleanType ezbooleantype.php
  \ingroup eZDatatype
  \brief Stores a boolean value

*/

include_once( "kernel/classes/ezdatatype.php" );

define( "EZ_DATATYPESTRING_BOOLEAN", "ezboolean" );

class eZBooleanType extends eZDataType
{
    function eZBooleanType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_BOOLEAN, ezi18n( 'kernel/classes/datatypes', "Checkbox", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_int' => 'value' ) ) );
    }

    /*!
     Store content
    */
    function storeObjectAttribute( &$attribute )
    {
    }


   /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataInt = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $dataInt );
        }
        else
        {
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $default = $contentClassAttribute->attribute( "data_int3" );
            $contentObjectAttribute->setAttribute( "data_int", $default );
        }
    }

    /*!
      Validates the http post var boolean input.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        if ( $contentObjectAttribute->validateIsRequired() and
             !$classAttribute->attribute( 'is_information_collector' ) )
        {
            if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ) )
            {
                $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
                if ( isset( $data ) )
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Input required.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
    */
    function validateCollectionAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $contentObjectAttribute->validateIsRequired() )
        {
            if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ) )
            {
                $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
                if ( isset( $data ) )
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Input required.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
    }

    /*!
     Fetches the http post var boolean input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) && $data !== '0' && $data !== 'false' )
                $data = 1;
            else
                $data = 0;
        }
        else
        {
            $data = 0;
        }
        $contentObjectAttribute->setAttribute( "data_int", $data );
        return true;
    }

   /*!
    \reimp
    Fetches the http post variables for collected information
   */
    function fetchCollectionAttributeHTTPInput( &$collection, &$collectionAttribute, &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) && $data !== '0' && $data !== 'false' )
                $data = 1;
            else
                $data = 0;
        }
        else
        {
            $data = 0;
        }
        $collectionAttribute->setAttribute( 'data_int', $data );
        return true;
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezboolean_default_value_' . $classAttribute->attribute( 'id' ) . '_exists' ) )
        {
            if ( $http->hasPostVariable( $base . "_ezboolean_default_value_" . $classAttribute->attribute( "id" ) ))
            {
                $data = $http->postVariable( $base . "_ezboolean_default_value_" . $classAttribute->attribute( "id" ) );
                if ( isset( $data ) )
                    $data = 1;
                $classAttribute->setAttribute( "data_int3", $data );
            }
            else
            {
                $classAttribute->setAttribute( "data_int3", 0 );
            }
        }
        return true;
    }

    function metaData( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return true;
    }

    /*!
     \reimp
    */
    function sortKey( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'int';
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    /*!
     Returns the integer value.
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return true;
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( 'data_int3' );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-value',
                                                                                 array( 'is-set' => $defaultValue ? 'true' : 'false' ) ) );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = strtolower( $attributeParametersNode->elementTextContentByName( 'default-value' ) ) == 'true';
        $classAttribute->setAttribute( 'data_int3', $defaultValue );
    }
}

eZDataType::register( EZ_DATATYPESTRING_BOOLEAN, "ezbooleantype" );

?>
