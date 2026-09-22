export const playDiceSound = () => {
    const AudioCtx = window.AudioContext || (window as any).webkitAudioContext;
    if (!AudioCtx) return;

    const ctx = new AudioCtx();
    const sampleRate = ctx.sampleRate;

    // Kilka "klaknięć" kostką o stół z malejącą głośnością
    const clacks = [0, 0.09, 0.17, 0.27, 0.36];
    const totalDuration = 0.55;
    const bufferSize = Math.floor(sampleRate * totalDuration);
    const buffer = ctx.createBuffer(1, bufferSize, sampleRate);
    const data = buffer.getChannelData(0);

    clacks.forEach((clackTime, idx) => {
        const start = Math.floor(clackTime * sampleRate);
        const clackLen = Math.floor(0.045 * sampleRate);
        const volume = 1 - idx * 0.15;
        for (let i = 0; i < clackLen && start + i < bufferSize; i++) {
            const env = Math.exp(-i / (clackLen * 0.25));
            data[start + i] += (Math.random() * 2 - 1) * env * volume;
        }
    });

    const source = ctx.createBufferSource();
    source.buffer = buffer;

    const filter = ctx.createBiquadFilter();
    filter.type = 'bandpass';
    filter.frequency.value = 1800;
    filter.Q.value = 0.8;

    const gain = ctx.createGain();
    gain.gain.value = 0.65;

    source.connect(filter);
    filter.connect(gain);
    gain.connect(ctx.destination);
    source.start();
    source.onended = () => ctx.close();
};

// Krótki, odróżnialny od stukotu kości dźwięk powiadomienia o nowej prywatnej wiadomości —
// dwa krótkie, rosnące tony sinusoidalne ("ding-ding").
export const playNotificationSound = () => {
    const AudioCtx = window.AudioContext || (window as any).webkitAudioContext;
    if (!AudioCtx) return;

    const ctx = new AudioCtx();
    const notes = [880, 1174.66]; // A5, D6
    const noteDuration = 0.16;
    const gap = 0.1;

    notes.forEach((frequency, idx) => {
        const startTime = ctx.currentTime + idx * gap;

        const oscillator = ctx.createOscillator();
        oscillator.type = 'sine';
        oscillator.frequency.value = frequency;

        const gain = ctx.createGain();
        gain.gain.setValueAtTime(0, startTime);
        gain.gain.linearRampToValueAtTime(0.35, startTime + 0.02);
        gain.gain.exponentialRampToValueAtTime(0.0001, startTime + noteDuration);

        oscillator.connect(gain);
        gain.connect(ctx.destination);
        oscillator.start(startTime);
        oscillator.stop(startTime + noteDuration);
    });

    setTimeout(() => ctx.close(), (notes.length * gap + noteDuration) * 1000 + 50);
};
