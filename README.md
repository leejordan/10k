# 10ktube
Built for the [10k apart](https://a-k-apart.com) challenge. A compelling web experience that can be delivered in 10kB and works without JavaScript. By [Lee Jordan](http://www.lendmeyourear.net/).

## 10k size limit
With gzip enabled and minifed CSS/JavaScript the initially loaded and usable page is 8.7kb.
This could be reduced further by not using the two svgs but they are both optimised heavily via [svgomg](https://jakearchibald.github.io/svgomg/). After the initial page load, the youtube iframe api JavaScript is lazy loaded along with the first 10 video thumbnail images. I've chosen the smallest size which still gives a relatively good visual quality.
As user scrolls, or tabs through links, or otherwise gets to a position where they have room for more videos in their viewport, more thumbnails are requested.

If for some reason the youtube api is unavailable due to data limits being reached or the api being down, the page will display the most recently stored set of results.

## Interoperability
"Your project must work equally well in all modern browsers". "modern browsers" is a bit vague but I've tested this in I.E 10 and above on windows as well as safari, chrome, firefox and opera on both OS X and windows. For mobile browsers it works fine on the version of safari that comes with iOS 5 and above. It works on the stock browser and chrome on android phones. I've even tested it in lynx just to check my markup and it works as well as you might expect a video site to work in a text browser (but it is navigable!)

I’ve kept the js fairly old school so it should be fine on most older browsers but even if my javascript is blocked or breaks due to some unsupported feature, the videos will still link to the main youtube site.

## Accessibility
It works fine with touch screen and mouse and I've supported keyboard usage via the tab key. I tested it with a screen reader too, and have used some best practices here such as shifting focus to modal when it opens and restricting focus via tab key to the modal when it is open.

## Progressive Enhancement
Without CSS you get a perfectly satisfactory ordered list. Without JS you get the first 10 videos in the list only and no more videos or video thumbnails are lazy loaded. Without JS, all video links take you directly to the youtube site.

## Libraries
I am not using any libraries, just my own CSS and JS.

## Potential improvements
The JavaScript could definitely be refined. The main performance concern is that a lot happens at once when it loads more videos in. But it works fine in all of the browsers I tested and really the challenge is mostly about the creation of a compelling web experience under the 10k limit.

## Demo
[http://www.lendmeyourear.net/10k](http://www.lendmeyourear.net/10k)
