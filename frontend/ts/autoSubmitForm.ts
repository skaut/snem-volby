export function initializeAutoSubmit(naja: any, container: Element, selector: string): void {
    container.querySelectorAll(selector)
        .forEach(element => {
            const form = (element.tagName.toLowerCase() === 'form' ? element : element.closest('form')) as HTMLFormElement | null;

            console.log("test" + form);

            if (form === null) {
                throw new Error('Element initialized for auto submit must be "form" or element inside form');
            }

            element.addEventListener('change', () => naja.uiHandler.submitForm(form, {history: true}));
        });
}
