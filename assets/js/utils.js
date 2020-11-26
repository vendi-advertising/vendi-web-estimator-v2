export function createElementWithChild( elementName, child, attributes ) {
    const obj = typeof elementName === 'string' ? document.createElement( elementName ) : document.createDocumentFragment();
    if ( typeof child === 'string' || typeof child === 'number' ) {
        obj.appendChild( document.createTextNode( child ) );
    } else if ( Array.isArray( child ) ) {
        child
            .forEach(
                ( i ) => {
                    obj.appendChild( i );
                }
            )
        ;
    } else if ( child ) {
        obj.appendChild( child );
    }
    if ( attributes ) {
        for ( const [ key, value ] of Object.entries( attributes ) ) {
            if ( 'class' === key ) {
                const classes = Array.isArray( value ) ? value : [ value ];
                classes
                    .forEach(
                        ( c ) => {
                            obj.classList.add( c );
                        }
                    )
                ;
            } else {
                obj.setAttribute( key, value );
            }
        }
    }

    return obj;
}