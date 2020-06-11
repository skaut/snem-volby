import naja from 'naja';
// @ts-ignore
import {ProgressBar} from './ProgressBar';
import {ModalExtension} from './ModalExtension';
import {SnippetProcessor} from "./SnippetProcessor";
import netteForms from "./netteForms";
import 'bootstrap.native/dist/bootstrap-native-v4';

export default function (): void {
    naja.registerExtension(ProgressBar);
    naja.registerExtension(ModalExtension);

    naja.registerExtension(SnippetProcessor, snippet => {
        window.BSN.initCallback(snippet);
    });

    naja.formsHandler.netteForms = netteForms;

    // Prevents NS_ERROR_ILLEGAL_VALUE on large pages in Firefox
    (naja as any).historyHandler.historyAdapter = {
        replaceState: () => {},
        pushState: () => {},
    };

    naja.initialize({history: false, forceRedirect: true});
}
