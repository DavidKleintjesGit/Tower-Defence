// Every sound in the game is synthesized with the Web Audio API rather than
// loaded from audio files — no asset pipeline needed, and it can't collide
// with the other session's ongoing sprite/art work. Settings persist in
// localStorage so the Settings page and every game page agree without a
// server round-trip.
const SFX_KEY = 'a51-sfx-enabled';
const MUSIC_KEY = 'a51-music-enabled';

class AudioManager {
    constructor() {
        this.ctx = null;
        this.sfxGain = null;
        this.musicGain = null;
        this.musicTimer = null;
        this.musicStep = 0;
        this.sfxEnabled = localStorage.getItem(SFX_KEY) !== '0';
        this.musicEnabled = localStorage.getItem(MUSIC_KEY) !== '0';
    }

    ensureContext() {
        if (!this.ctx) {
            const Ctor = window.AudioContext || window.webkitAudioContext;

            if (!Ctor) {
                return false;
            }

            this.ctx = new Ctor();
            this.sfxGain = this.ctx.createGain();
            this.sfxGain.gain.value = 0.25;
            this.sfxGain.connect(this.ctx.destination);

            this.musicGain = this.ctx.createGain();
            this.musicGain.gain.value = 0.14;
            this.musicGain.connect(this.ctx.destination);
        }

        if (this.ctx.state === 'suspended') {
            this.ctx.resume();
        }

        return true;
    }

    isSfxEnabled() {
        return this.sfxEnabled;
    }

    isMusicEnabled() {
        return this.musicEnabled;
    }

    setSfxEnabled(enabled) {
        this.sfxEnabled = enabled;
        localStorage.setItem(SFX_KEY, enabled ? '1' : '0');
    }

    setMusicEnabled(enabled) {
        this.musicEnabled = enabled;
        localStorage.setItem(MUSIC_KEY, enabled ? '1' : '0');

        if (enabled) {
            this.startMusic();
        } else {
            this.stopMusic();
        }
    }

    tone(freq, duration, type, gainValue, delay = 0) {
        if (!this.sfxEnabled || !this.ensureContext()) {
            return;
        }

        const t0 = this.ctx.currentTime + delay;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();

        osc.type = type;
        osc.frequency.setValueAtTime(freq, t0);
        gain.gain.setValueAtTime(0, t0);
        gain.gain.linearRampToValueAtTime(gainValue, t0 + 0.01);
        gain.gain.exponentialRampToValueAtTime(0.001, t0 + duration);

        osc.connect(gain);
        gain.connect(this.sfxGain);
        osc.start(t0);
        osc.stop(t0 + duration + 0.02);
    }

    playShoot() {
        this.tone(660, 0.07, 'square', 0.12);
    }

    playExplosion() {
        if (!this.sfxEnabled || !this.ensureContext()) {
            return;
        }

        const t0 = this.ctx.currentTime;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();

        osc.type = 'sawtooth';
        osc.frequency.setValueAtTime(180, t0);
        osc.frequency.exponentialRampToValueAtTime(28, t0 + 0.35);
        gain.gain.setValueAtTime(0.3, t0);
        gain.gain.exponentialRampToValueAtTime(0.001, t0 + 0.4);

        osc.connect(gain);
        gain.connect(this.sfxGain);
        osc.start(t0);
        osc.stop(t0 + 0.4);
    }

    playCoin() {
        this.tone(880, 0.08, 'triangle', 0.18);
        this.tone(1320, 0.1, 'triangle', 0.14, 0.05);
    }

    playWaveStart() {
        this.tone(220, 0.15, 'sawtooth', 0.18);
        this.tone(330, 0.2, 'sawtooth', 0.14, 0.1);
    }

    playUpgrade() {
        this.tone(440, 0.1, 'triangle', 0.18);
        this.tone(660, 0.15, 'triangle', 0.18, 0.08);
    }

    playSell() {
        this.tone(300, 0.1, 'sine', 0.18);
    }

    playPlaceTower() {
        this.tone(392, 0.08, 'square', 0.15);
    }

    playVictory() {
        [523, 659, 784, 1046].forEach((freq, i) => this.tone(freq, 0.25, 'triangle', 0.18, i * 0.12));
    }

    playGameOver() {
        [392, 349, 294, 220].forEach((freq, i) => this.tone(freq, 0.3, 'sawtooth', 0.18, i * 0.15));
    }

    // A short, gently looping 6-note pad — deliberately simple so it sits in
    // the background rather than competing with sfx.
    startMusic() {
        if (!this.musicEnabled || this.musicTimer || !this.ensureContext()) {
            return;
        }

        const notes = [220, 261.6, 329.6, 392, 329.6, 261.6];
        const stepDuration = 0.9;

        const scheduleStep = () => {
            if (!this.musicEnabled || !this.ctx) {
                return;
            }

            const freq = notes[this.musicStep % notes.length];
            const t0 = this.ctx.currentTime;
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, t0);
            gain.gain.setValueAtTime(0, t0);
            gain.gain.linearRampToValueAtTime(0.15, t0 + 0.15);
            gain.gain.exponentialRampToValueAtTime(0.001, t0 + stepDuration);

            osc.connect(gain);
            gain.connect(this.musicGain);
            osc.start(t0);
            osc.stop(t0 + stepDuration + 0.05);

            this.musicStep += 1;
        };

        scheduleStep();
        this.musicTimer = setInterval(scheduleStep, stepDuration * 1000);
    }

    stopMusic() {
        if (this.musicTimer) {
            clearInterval(this.musicTimer);
            this.musicTimer = null;
        }
    }
}

export const audio = new AudioManager();
