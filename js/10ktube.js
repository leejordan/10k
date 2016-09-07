var data = JSON.parse(getFile('./data/mostpopular.json'));
var fromIndex = 0;
var currentVideoId;

function init() {
    loadThumbnails();
    bindCloseEvents();
    bindKeyboardControls();
    bindHoverEvent();
    bindBlurEvent();
    loadYoutubeApi();
    restrictFocusToModal();

    if (footerIsInViewport()) {
        loadMore();
    }

    window.onscroll = function() {
        if (footerIsInViewport()) {
            loadMore();
        }
    };
}

function getFile(file) {
    var request = new XMLHttpRequest();
    request.open('GET', file, false);
    request.send(null);
    if (request.status == 200) {
        return request.responseText;
    }
};

function isElementInViewport(el) {
    var rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight + el.offsetHeight || document.documentElement.clientHeight + el.offsetHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

function loadThumbnails() {
    var listItems = document.querySelectorAll('[data-id]');

    for (var i=0, item; item = listItems[i]; i++) {
        if (! item.getAttribute("style")) {
            item.style.backgroundImage = 'url(https://i.ytimg.com/vi/' + item.dataset.id + '/mqdefault.jpg)';
        }
    }
}

function footerIsInViewport() {
    var footer = document.getElementById('footer');
    if (isElementInViewport(footer)) {
        return true;
    }
    return false;
}

function loadMore(index, limit) {
    index = typeof index !== 'undefined' ? index : 0;
    limit = typeof limit !== 'undefined' ? limit : 10;

    for (var i=fromIndex, item; item = data[i]; i++) {
        if (i < fromIndex + limit && i < Object.keys(data).length) {
            appendListItem(i, item.id, item.title);
        } else {
            if (footerIsInViewport()) {
                fromIndex = fromIndex + limit;
                loadMore(i);
            }
        }
    }
    fromIndex = fromIndex + limit;
    bindBlurEvent();
    bindHoverEvent();
    loadThumbnails();
}

function appendListItem(index, id, title) {
    var parent = document.getElementById('video-list');
    var link = document.createElement('a');
    var heading = document.createElement('h3');
    var headingText = document.createTextNode(title);

    heading.appendChild(headingText);

    link.setAttribute('href', 'https://www.youtube.com/watch?v=' + id);
    link.setAttribute('class', 'list__vid');
    link.setAttribute('data-index', index);
    link.setAttribute('data-id', id);
    link.setAttribute('onclick', 'return openModal("' + id + '")');

    link.appendChild(heading);

    parent.appendChild(link);
}

function loadYoutubeApi() {
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

function openModal(id) {
    var playerElement = document.getElementById('player-container');
    var focusedElement = document.getElementById('focused-element');
    currentVideoId = id;

    playerElement.className = 'player-wrap active';
    document.body.className = 'player-open';
    focusedElement.focus();

    loadPlayer(id);
    restrictFocusToModal();
    return false;
}

function restrictFocusToModal() {
    document.addEventListener('focus', checkModalActive, true);
}

function checkModalActive(e) {
    var playerElement = document.getElementById('player-container');
    var focusedElement = document.getElementById('focused-element');
    if (playerElement.className === 'player-wrap active') {
        e.stopPropagation();
        focusedElement.focus();
    }
}

function loadPlayer(id) {
    player = new YT.Player('player', {
        height: '100%',
        width: '100%',
        videoId: id,
        events: {
            'onReady': onPlayerReady
        }
    });
}

function onPlayerReady(event) {
    event.target.playVideo();
}

function closePlayer(e) {
    var playerElement = document.getElementById('player-container');
    var videoThumbnail = document.querySelectorAll('[data-id="' + currentVideoId + '"]');
    document.removeEventListener('focus', checkModalActive, true);
    videoThumbnail[0].focus();
    playerElement.className = 'player-wrap';
    document.body.className = '';
    player.destroy();
    e.preventDefault();
}

function bindCloseEvents() {
    var closeElements = document.querySelectorAll('[data-close]');

    for (var i = 0; i < closeElements.length; i++) {
        closeElements[i].addEventListener('click', closePlayer, false);
    }
}

function bindHoverEvent() {
    // remove focus on hover - mouse wins
    var listItems = document.querySelectorAll('[data-id]');
    for (var i = 0; i < listItems.length; i++) {
        listItems[i].removeEventListener('mouseover', deselectFocus);
        listItems[i].addEventListener('mouseover', deselectFocus, false);
    }
}

function deselectFocus() {
    document.activeElement.blur();
}

function bindBlurEvent() {
    var listItems = document.querySelectorAll('[data-id]');
    var thresholdThumbnail = listItems[listItems.length - 4];

    // detect when (nearly) the last thumbnail loses focus
    // avoids need to detect tab key press and allows this to work with screen reader focus too
    for (var i = 0; i < listItems.length; i++) {
        listItems[i].removeEventListener('blur', loadMore, false);
    }
    thresholdThumbnail.addEventListener('blur', loadMore, false);
}

function bindKeyboardControls() {
    document.body.addEventListener('keyup', function(e) {
        // escape key closes any open video modals
        if (e.keyCode == 27) {
            closePlayer(e);
        }
    });
}

init();
