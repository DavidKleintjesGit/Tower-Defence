<div
    class="flex min-h-screen flex-col items-center justify-center px-4 py-16"
    x-data="{
        sfx: localStorage.getItem('a51-sfx-enabled') !== '0',
        music: localStorage.getItem('a51-music-enabled') !== '0',
        toggleSfx() {
            this.sfx = !this.sfx;
            localStorage.setItem('a51-sfx-enabled', this.sfx ? '1' : '0');
            if (this.sfx) this.testTone(660);
        },
        toggleMusic() {
            this.music = !this.music;
            localStorage.setItem('a51-music-enabled', this.music ? '1' : '0');
        },
        testTone(freq) {
            const Ctor = window.AudioContext || window.webkitAudioContext;
            if (!Ctor) return;
            const ctx = new Ctor();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'square';
            osc.frequency.value = freq;
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.16);
        },
    }"
>
    <div class="w-full max-w-md">
        <a href="{{ route('home') }}" wire:navigate class="text-sm text-slate-400 hover:text-slate-200">
            &larr; Menu
        </a>

        <div class="mt-4 text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-emerald-400">Lab 2 // Config</p>
            <h1 class="mt-1 text-3xl font-black uppercase tracking-widest text-emerald-300 drop-shadow-[0_0_18px_rgba(16,185,129,0.5)]">
                Settings
            </h1>
        </div>

        <div class="mt-8 space-y-3">
            <div class="flex items-center justify-between rounded-md border border-emerald-500/30 bg-slate-950/60 px-5 py-4 backdrop-blur-sm">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-emerald-300">Sound effects</p>
                    <p class="mt-0.5 text-xs text-slate-500">Shots, explosions, upgrades, coins.</p>
                </div>
                <button
                    type="button"
                    @click="toggleSfx()"
                    class="relative h-6 w-11 shrink-0 rounded-full transition"
                    :class="sfx ? 'bg-emerald-500' : 'bg-slate-700'"
                >
                    <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white transition" :class="sfx ? 'translate-x-5' : ''"></span>
                </button>
            </div>

            <div class="flex items-center justify-between rounded-md border border-emerald-500/30 bg-slate-950/60 px-5 py-4 backdrop-blur-sm">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-emerald-300">Music</p>
                    <p class="mt-0.5 text-xs text-slate-500">Background ambient loop during play.</p>
                </div>
                <button
                    type="button"
                    @click="toggleMusic()"
                    class="relative h-6 w-11 shrink-0 rounded-full transition"
                    :class="music ? 'bg-emerald-500' : 'bg-slate-700'"
                >
                    <span class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white transition" :class="music ? 'translate-x-5' : ''"></span>
                </button>
            </div>
        </div>

        <p class="mt-6 text-center text-[10px] uppercase tracking-widest text-slate-600">
            Changes apply the next time you enter a level.
        </p>
    </div>
</div>
