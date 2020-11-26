import { createElementWithChild } from './utils'

function formatHours( hours ) {
    if ( null === hours ) {
        return '';
    }

    return hours
        .toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 2
            }
        )
        .replace( '.00', '' )
        ;
}

function createSmallTextInput( name ) {
    return createElementWithChild(
        'input',
        null,
        {
            type: 'text',
            name,
            class: 'small',
        }
    );
}

function createLineItemEntry() {
    return createElementWithChild(
        'tr',
        [
            createElementWithChild( 'td' ),
            createElementWithChild( 'td', createSmallTextInput( 'name' ) ),
            createElementWithChild( 'td', createSmallTextInput( 'hours-low' ) ),
            createElementWithChild( 'td', createSmallTextInput( 'hours-high' ) ),
            createElementWithChild( 'td', createSmallTextInput( 'hours-staff' ) ),
            createElementWithChild( 'td', createSmallTextInput( 'hours-rate' ) ),
            createElementWithChild( 'td', createSmallTextInput( 'cost' ) ),
            createElementWithChild( 'td' ),
            createElementWithChild( 'td' ),
        ]
    );
}

function createLineItem_HourRangeLineItem( tr, lineItem, estimate ) {
    tr
        .appendChild(
            createElementWithChild(
                null,
                [
                    createElementWithChild( 'td', createEditableText( formatHours( lineItem.hoursLow ), lineItem.id, 'line_item-hours-low' ) ),
                    createElementWithChild( 'td', createEditableText( formatHours( lineItem.hoursHigh ), lineItem.id, 'line_item-hours-high' ) ),
                    createElementWithChild( 'td', createEditableText( formatHours( lineItem.staffQuantity ), lineItem.id, 'line_item-staff' ) ),
                    createElementWithChild( 'td', createEditableText( formatHours( lineItem.rate || estimate.defaultRate ), lineItem.id, 'line_item-rate' ) ),
                    createElementWithChild( 'td' ),
                    createElementWithChild( 'td' ),
                    createElementWithChild( 'td' ),
                ]
            )
        );
}

function createLineItem_FixedCostLineItem( tr, lineItem, estimate ) {
    tr
        .appendChild(
            createElementWithChild(
                null,
                [
                    createElementWithChild( 'td' ),
                    createElementWithChild( 'td' ),
                    createElementWithChild( 'td' ),
                    createElementWithChild( 'td' ),
                    createElementWithChild( 'td', createEditableText( formatHours( lineItem.cost ), lineItem.id, 'line_item-cost' ) ),
                    createElementWithChild( 'td' ),
                    createElementWithChild( 'td' ),
                ]
            )
        );
}

function createLineItem( lineItem, estimate ) {
    const tr = createElementWithChild(
        'tr',
        [
            createElementWithChild( 'td', createDraggableSpan() ),
            createElementWithChild( 'td', createEditableText( lineItem.label, lineItem.id, 'line_item-label' ) ),
        ]
    );

    switch ( lineItem.type ) {
        case 'HourRangeLineItem':
            createLineItem_HourRangeLineItem( tr, lineItem, estimate );
            break;
        case 'FixedCostLineItem':
            createLineItem_FixedCostLineItem( tr, lineItem, estimate );
            break;
        default:
            throw 'Unexpected line item type: ' + lineItem.type;
    }

    return tr;
}

function createDraggableSpan() {
    return createElementWithChild( 'span', '\u2195', { 'data-role': 'draggable' } );
}

function createEditableText( text, id, internalType ) {
    const targetId = internalType + '_' + id;
    return createElementWithChild(
        null,
        [
            createElementWithChild(
                'span',
                text,
                {
                    'data-editable': 'true',
                    'data-target': targetId,
                    contentEditable: true,
                }
            ),
            createElementWithChild(
                'input',
                null,
                {
                    type: 'hidden',
                    id: targetId,
                    value: text,
                }
            )
        ]
    );
}

function createSection( section, estimate ) {
    const root = createElementWithChild(
        null,
        createElementWithChild(
            'tr',
            [
                createElementWithChild( 'td', createDraggableSpan() ),
                createElementWithChild( 'td', createEditableText( section.name, section.id, 'section' ), { colspan: '8' } )
            ]
        )
    );

    section
        .lineItems
        .forEach(
            ( item ) => {
                root.appendChild( createLineItem( item, estimate ) );
            }
        )
    ;

    root.appendChild( createLineItemEntry() );

    return root;
}

function createSectionEntry() {
    return createElementWithChild(
        'tr',
        [
            createElementWithChild( 'td' ),
            createElementWithChild(
                'td',
                createElementWithChild(
                    'input',
                    null,
                    {
                        type: 'text',
                        name: 'new-section-label',
                    }
                ),
                {
                    colspan: '8',
                }
            )
        ]
    );
}

function createHeaderRow() {
    const
        headers = [
            '',
            '',
            'Hours Low',
            'Hours High',
            'Staff',
            'Rate',
            'Fixed Cost',
            'Total Low',
            'Total High',
        ]
    ;

    const tr = createElementWithChild( 'tr' );
    headers
        .forEach(
            ( text ) => {
                tr.appendChild( createElementWithChild( 'th', text, { scope: 'col' } ) );
            }
        )
    ;
    return createElementWithChild( 'thead', tr );
}

function createEstimateRoot( estimate ) {
    const table = createElementWithChild( 'table', [ createHeaderRow() ], { class: 'entry-table' } );
    const tbody = createElementWithChild( 'tbody' );
    table.appendChild( tbody );
    estimate
        .sections
        .forEach(
            ( section ) => {
                tbody.appendChild( createSection( section, estimate ) );
            }
        )
    ;
    tbody.appendChild( createSectionEntry() );
    return table;
}

function bindListeners( container ) {
    container
        .querySelectorAll( '[data-editable]' )
        .forEach(
            ( item ) => {
                const targetId = item.getAttribute( 'data-target' );
                const target = document.getElementById( targetId );
                item
                    .addEventListener(
                        'input',
                        ( e ) => {
                            target.setAttribute( 'new-value', e.target.innerText )
                        }
                    )
                ;
                item
                    .addEventListener(
                        'blur',
                        ( e ) => {
                            if ( target.value === target.getAttribute( 'new-value' ) ) {
                                return;
                            }

                            const r = new XMLHttpRequest();
                            r.open( 'POST', window.APP.estimatePartUpdateRoute, true );
                            r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            r
                                .addEventListener(
                                    'load',
                                    ( evt ) => {
                                        if ( evt.target.status !== 200 ) {
                                            //TODO: Can a non-200 happen in load?
                                            throw 'Non-200 happened, update';
                                        }
                                    }
                                )
                            ;
                            r
                                .addEventListener(
                                    'error',
                                    ( evt ) => {
                                        throw 'AJAX POST error';
                                    }
                                )
                            ;

                            const data = {
                                key: targetId,
                                value: target.value,
                            };

                            const params = Object.keys( data ).map(
                                ( k ) => {
                                    return encodeURIComponent( k ) + '=' + encodeURIComponent( data[ k ] )
                                }
                            ).join( '&' );

                            r.send( params );
                            //TODO: This should be the update endpoint
                        }
                    )
                ;
            }
        )
    ;
}

export default function ( container, json ) {
    container.appendChild( createEstimateRoot( json ) );
    bindListeners( container );
};