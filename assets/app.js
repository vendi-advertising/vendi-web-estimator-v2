/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/000-reset.css';
import './styles/100-main.css';
import './styles/400-entry.css';

import entryBuilder from './js/entry-builder';

if ( !window.APP.estimateJsonRoute ) {
    throw 'Missing global app property: window.APP.estimateJsonRoute';
}

const cont = document.getElementById( 'entry-table-container' );
if ( !cont ) {
    throw 'Missing root container for entry';
}

const r = new XMLHttpRequest();
r.open( 'GET', window.APP.estimateJsonRoute, true );
r
    .addEventListener(
        'load',
        ( evt ) => {
            if ( evt.target.status !== 200 ) {
                //TODO: Can a non-200 happen in load?
                throw 'Non-200 happened';
            }

            const data = JSON.parse( evt.target.responseText );
            entryBuilder( cont, data );
        }
    )
;
r
    .addEventListener(
        'error',
        ( evt ) => {
            throw 'AJAX error';
        }
    )
;

r.send();

document
    .querySelectorAll( 'input[type=checkbox][data-role~=global-action][data-action~=toggle-edit-mode]' )
    .forEach(
        ( checkbox ) => {
            checkbox
                .addEventListener(
                    'change',
                    ( evt ) => {
                        const isGlobalEditModeEnabled = evt.target.checked;
                        debugger;
                    }
                )
            ;
        }
    )
;