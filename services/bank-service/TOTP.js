var hmacsha1 = require('hmacsha1');

function generate(key, time_step) {
    let ts = Math.floor(Math.floor(Date.now() / 1000) / time_step);
    console.log(ts);
    let hash = hmacsha1(key, ts.toString());
    let hash_bytes = Buffer.from(hash, 'base64');
    let hex = '';
    for (c of hash_bytes) {
        hex += c.toString(16);
    }
    console.log(hex);
    // hash = unescape(encodeURIComponent(hash));
    // console.log(hash);
    // let hash_bytes = [];
    // for (let i =0; i < hash.length; i++) {
    //     hash_bytes.push(hash.charCodeAt(i));
    // }
    let offset = hash_bytes[19] & 0xf;
    console.log(offset);
    let bin_code = (hash_bytes[offset] & 0x7f) << 24;
    bin_code |= (hash_bytes[offset+1]) << 16;
    bin_code |= (hash_bytes[offset+2]) << 8;
    bin_code |= (hash_bytes[offset+3]);
    return bin_code.toString().substr(0, 8);
}

module.exports = generate;
