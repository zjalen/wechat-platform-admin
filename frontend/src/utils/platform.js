export function getOfficialAccountServiceTypeInfo (code) {
  const types = {
    0: '订阅号',
    1: '订阅号',
    2: '服务号'
  }
  return types[code]
}

export function getOfficialAccountVerifyTypeInfo (code) {
  const types = {
    '-1': '未认证',
    0: '微信认证',
    1: '新浪微博认证',
    2: '腾讯微博认证',
    3: '资质认证但未通过名称认证',
    4: '资质认证，新浪微博认证，但未通过名称认证',
    5: '资质认证，腾讯微博认证，但未通过名称认证'
  }
  return types[code]
}

export function getMiniProgramServiceTypeInfo (code) {
  const types = {
    0: '普通小程序',
    1: '试用小程序',
    2: '门店小程序',
    3: '门店小程序',
    4: '小游戏',
    10: '小商店'
  }
  return types[code]
}

export function getMiniProgramVerifyTypeInfo (code) {
  const types = {
    '-1': '未认证',
    0: '微信认证'
  }
  return types[code]
}


export function getBusinessTypeInfo (code) {
  const types = {
    'open_store': '门店微信功能',
    'open_scan': '微信扫商品功能',
    'open_pay': '微信支付功能',
    'open_card': '微信卡券功能',
    'open_shake': '微信摇一摇功能',
  }
  return types[code]
}
