// formats the results of a youtube data api request into a more manageable format
// example request: https://www.googleapis.com/youtube/v3/videos/?chart=mostPopular&maxResults=50&part=id,snippet&key=YOUR_API_KEY

function getFile(file) {
    var request = new XMLHttpRequest();
    request.open('GET', file, false);
    request.send(null);
    if (request.status == 200)
        return request.responseText;
};

function truncate( n, useWordBoundary ){
    var isTooLong = this.length > n,
        s_ = isTooLong ? this.substr(0,n-1) : this;
        s_ = (useWordBoundary && isTooLong) ? s_.substr(0,s_.lastIndexOf(' ')) : s_;
    return  isTooLong ? s_ + '...' : s_;
};

var data = JSON.parse(getFile('./data/mostpopular-all.json'));
var formattedData= {};

for (var i = 0; i < data.items.length; i++){
    var obj = data.items[i];
    var newObj = {};

    newObj.id = obj.id;
    newObj.title = obj.snippet.title;

    formattedData[i] = newObj;
}

console.log(JSON.stringify(formattedData));
