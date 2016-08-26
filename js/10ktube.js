var data = JSON.parse(getFile('./data/mostpopular.json'));
var fromIndex = 0;

function init() {
    loadMore();
    loadThumbnails();

    window.onscroll = function() {
        loadMore();
        loadThumbnails();
    };
}

function getFile(file) {
    var request = new XMLHttpRequest();
    request.open('GET', file, false);
    request.send(null);
    if (request.status == 200)
        return request.responseText;
};

function isElementInViewport(el) {
    var rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight + 20 || document.documentElement.clientHeight + 20) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

function loadThumbnails() {
    var listItems = document.getElementsByClassName('list__vid');

    for (var i=0, item; item = listItems[i]; i++) {
        if (! item.getAttribute("style")) {
            item.style.backgroundImage = 'url(https://i.ytimg.com/vi/' + item.dataset.id + '/mqdefault.jpg)';
        }
    }
}

function footerIsInViewport() {
    var footer = document.getElementById("footer");
    if (isElementInViewport(footer)) {
        console.log('footer visible');
        return true;
    }
    return false;
}

function loadMore(index = 0, limit = 40) {
    if (footerIsInViewport()) {
        for (var i=fromIndex, item; item = data[i]; i++) {
            if (i < fromIndex + limit && i < Object.keys(data).length) {
                appendListItem(i, item.id, item.title, item.description);
            } else {
                if (footerIsInViewport()) {
                    fromIndex = fromIndex + limit;
                    loadMore(i);
                }
            }
        }
        fromIndex = fromIndex + limit;
        console.log(fromIndex);
    }
}

function appendListItem(index, id, title, desc) {
    var parent = document.getElementById("video-list");
    var link = document.createElement('a');
    var heading = document.createElement('h3');
    var headingText = document.createTextNode(title);
    var description = document.createElement('p');
    var descriptionText = document.createTextNode(desc);

    heading.appendChild(headingText);
    description.appendChild(descriptionText);

    link.setAttribute('href', 'https://www.youtube.com/watch?v=' + id);
    link.setAttribute('class', 'list__vid');
    link.setAttribute('data-index', index);
    link.setAttribute('data-id', id);

    link.appendChild(heading);
    link.appendChild(description);

    parent.appendChild(link);
}

init();
