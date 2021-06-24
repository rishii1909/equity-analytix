import axios from 'axios'
import { apiBaseUrl, urlBaseToSounds, token } from '../../appConfig'

export const getAllNews = (payload) => {
  const endpoint = payload.onlyForLastDay ? payload.timestamp : 'admin?offset=1'
  return axios.get(`${apiBaseUrl}/api/news/${endpoint}`, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const getUserInfo = () => {
  console.log(token.key);
  return axios.get(`${apiBaseUrl}/api/user/info`, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const getAllUsers = () => {
  return axios.get(`${apiBaseUrl}/api/user/all`, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const markMessageAsViewedOnServer = (payload) => {
  console.log(payload.messages);
  return axios.post(`${apiBaseUrl}/api/news/${payload.timestamp}`, payload.messages, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const deleteMessage = (payload) => {
  return axios.post(`${apiBaseUrl}/api/news/archive`, payload, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const deleteAllMessages = () => {
  return axios.post(`${apiBaseUrl}/api/news/archive/all`, null, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const refreshAllMessages = () => {
  return axios.post(`${apiBaseUrl}/api/news/restore/all`, null, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const createUserSetting = (payload) => {
  return axios.post(`${apiBaseUrl}/api/user/chat/setting/create`, payload, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const editUserSetting = (payload) => {
  return axios.post(`${apiBaseUrl}/api/user/chat/setting/edit`, payload, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const getUserSettings = () => {
  return axios.get(`${apiBaseUrl}/api/user/chat/setting/`, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const getSearchResults = (query) => {
  return axios.get(`${apiBaseUrl}/api/news/search/${query}`, {
    headers: {
      Authorization: token.key,
    },
  })
}

export const getSound = (path) => {
  return axios.get(`${urlBaseToSounds}${path}`, {
    responseType: 'arraybuffer',
  })
}
