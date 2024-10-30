document.addEventListener('DOMContentLoaded', function() {
    var settings = boldifySettings;

    function processTextNode(textNode) {
        var text = textNode.textContent;
        var modifiedText = text; // Preserva il testo originale per confronto

        boldifySettings.forEach(function(item) {
            if (item.word) {
                var regex = new RegExp('\\b' + item.word + '\\b', 'gi');
                modifiedText = modifiedText.replace(regex, function(matchedWord) {
                    var result = matchedWord;
                    if (item.grassetto === "1") {
                        result = '<strong>' + result + '</strong>';
                    }
                    if (item.corsivo === "1") {
                        result = '<em>' + result + '</em>';
                    }
                    if (item.sottolineato === "1") {
                        result = '<u>' + result + '</u>';
                    }
                    if (item.evidenziato === "1") {
                        result = '<mark>' + result + '</mark>';
                    }
                    return result;
                });
            }
        });

        // Applica modifiche solo se il testo è stato effettivamente modificato
        if (modifiedText !== text) {
            textNode.parentNode.innerHTML = textNode.parentNode.innerHTML.replace(text, modifiedText);
        }
    }

    function traverseDOM(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            processTextNode(node);
        } else if (node.nodeType === Node.ELEMENT_NODE && !node.matches('script, style, strong, em, u, mark')) {
            Array.from(node.childNodes).forEach(traverseDOM);
        }
    }

    document.querySelectorAll('.boldify-content').forEach(traverseDOM);
});
