import neutralMessageSoundSrc from "../sounds/neutral-message.mp3";
import positiveMessageSoundSrc from "../sounds/positive-message.mp3";
import negativeMessageSoundSrc from "../sounds/negative-message.mp3";
import warningMessageSoundSrc from "../sounds/warning-message.mp3";
import connectedSoundSrc from "../sounds/connected.mp3";
import connectionLostSoundSrc from "../sounds/connection-lost.mp3";
// import { getSound } from "@/api/chatApi";
import { getSound } from "/api/chatApi";

export const soundPath = {
  neutral: neutralMessageSoundSrc,
  positive: positiveMessageSoundSrc,
  negative: negativeMessageSoundSrc,
  warning: warningMessageSoundSrc,
  connected: connectedSoundSrc,
  connectionLost: connectionLostSoundSrc,
};

export function loadSound(path, audioContext) {
  const sound = { volume: 1, audioBuffer: null };

  getSound(path).then((response) => {
    audioContext.decodeAudioData(
      response.data,
      (buffer) => {
        sound.audioBuffer = buffer;
      },
      (error) => {
        console.log(error);
      }
    );
  });
  return sound;
}

export function playSound(sound, audioContext, isMuted = false) {
  if (!sound.audioBuffer) return false;

  const source = audioContext.createBufferSource();
  if (!source) return false;
  source.buffer = sound.audioBuffer;
  if (!source.start) source.start = source.noteOn;
  if (!source.start) return false;

  const gainNode = audioContext.createGain();
  gainNode.gain.value = isMuted ? 0 : 0.5;
  source.connect(gainNode);
  gainNode.connect(audioContext.destination);

  source.start(0);
  sound.gainNode = gainNode;
}
