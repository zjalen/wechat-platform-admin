/**
 * 判断中英文字符总长度
 * 中文占两个
 */
export function getLengthOfStr(str = null) {
  let len = str.length;
  let real_len = 0;
  for (let i = 0; i < len; i++) {
    if ((str.charCodeAt(i) & 0xff00) !== 0) {
      real_len++;
    }
    real_len++;
  }
  return real_len;
}
