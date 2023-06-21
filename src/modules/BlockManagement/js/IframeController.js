// import BlockManagementError from './errors/BlockManagementError.js';

const viewBlockIframe = document.querySelector('iframe');
if (viewBlockIframe instanceof HTMLIFrameElement) {
    viewBlockIframe.addEventListener('load', (e) => {
        viewBlockIframe.style.width = '100%';
        viewBlockIframe.style.height = viewBlockIframe.contentWindow.document.documentElement.scrollHeight + 'px';
    });
}
