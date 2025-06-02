function speakText(text) {
  const utterance = new SpeechSynthesisUtterance(text);
  utterance.lang = "en-US"; // atau 'en-US'
  window.speechSynthesis.speak(utterance);
}

function cekAlarmTugas(tugasData) {
  tugasData.forEach(function (tugas) {
    const expiredAt = new Date(tugas.expired_at);
    const now = new Date();
    const diffMs = expiredAt - now;
    const diffHours = diffMs / (1000 * 60 * 60);

    if (diffHours > 0 && diffHours <= 24) {
      const pesan =
        "ALARM: Tugas '" +
        tugas.Tugas +
        "' milik " +
        tugas.Nama +
        " akan kadaluarsa pada " +
        tugas.expired_at;
      alert(pesan);
      speakText(pesan);
    }
  });
}
