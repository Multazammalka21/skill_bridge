<?php
$file = __DIR__ . '/resources/views/play/tunarungu.blade.php';
$content = file_get_contents($file);

// The unique start marker (line 78 - comment before ekspresi_emosi block)
$oldStart = '                <!-- If it is the "Ekspresi Emosi" question, show the drag & drop matching UI -->';

// The unique end marker (end of standard questions block, line 202)
$oldEnd = "                </template>\n\n                <p class=\"feedback\"";

// Find positions
$startPos = strpos($content, $oldStart);
$endPos   = strpos($content, $oldEnd, $startPos);

if ($startPos === false || $endPos === false) {
    echo "ERROR: Could not find markers.\n";
    echo "Start found: " . ($startPos !== false ? "YES at $startPos" : "NO") . "\n";
    echo "End found: " . ($endPos !== false ? "YES at $endPos" : "NO") . "\n";
    exit(1);
}

$newBlock = '                <!-- Ekspresi Emosi: show full image (draggable) + text option cards as drop targets -->
                <template x-if="currentQuestion.gambar && currentQuestion.gambar.includes(\'ekspresi_emosi\')">
                    <div style="width: 100%; display: flex; flex-direction: column; align-items: center; gap: 1.5rem;">
                        <!-- Instruction -->
                        <p style="font-size: 1.2rem; color: #ff6b35; font-weight: bold; text-align: center; background: rgba(255,107,53,0.08); padding: 10px 20px; border-radius: 12px; border: 1px dashed rgba(255,107,53,0.3);">
                            👆 Tap kartu kata yang cocok, atau seret gambar ke kartu yang sesuai
                        </p>

                        <!-- Full draggable image -->
                        <div style="position: relative; cursor: grab; user-select: none;"
                             draggable="true"
                             @dragstart="draggedEmotion = currentQuestion.jawaban_benar"
                             title="Seret gambar ini ke kotak kata yang menggambarkan ekspresi wajah yang diminta!"
                        >
                            <img
                                :src="currentQuestion.gambar"
                                alt="Gambar Ekspresi Wajah"
                                style="max-width: 300px; width: 100%; border-radius: 16px; border: 3px solid rgba(255,107,53,0.4); box-shadow: 0 8px 24px rgba(0,0,0,0.3); pointer-events: none;"
                            />
                            <div style="position: absolute; bottom: -12px; left: 50%; transform: translateX(-50%); background: #ff6b35; color: white; font-size: 0.85rem; font-weight: bold; padding: 4px 14px; border-radius: 20px; white-space: nowrap;">
                                &#8597; Seret ke kotak kata
                            </div>
                        </div>

                        <!-- Drop Target / Grid Options -->
                        <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; width: 100%; margin-top: 1.5rem;">
                            <template x-for="(opt, idx) in currentQuestion.pilihan" :key="idx">
                                <div
                                    class="card lesson-card choice-card cursor-pointer"
                                    style="text-align: center; padding: 20px; position: relative; min-height: 90px; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.3s; border: 2px dashed rgba(255,107,53,0.3);"
                                    :class="shakingIndex === idx ? \'animate-shake\' : \'\'"
                                    :style="[getSelectedCardStyle(idx), dragOverIndex === idx ? \'border: 2px solid #ff6b35 !important; background: rgba(255,107,53,0.15) !important; transform: scale(1.03);\' : \'\']"
                                    @dragover.prevent="dragOverIndex = idx"
                                    @dragleave="dragOverIndex = null"
                                    @drop.prevent="dragOverIndex = null; selectOption(idx, opt)"
                                    @click="selectOption(idx, opt)"
                                >
                                    <!-- Feedback symbols -->
                                    <div
                                        style="position: absolute; top: 10px; right: 10px; font-size: 24px;"
                                        x-html="getFeedbackSymbol(idx)"
                                    ></div>

                                    <div style="font-size: 1.6rem; font-weight: bold; color: var(--text);" x-text="opt"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Otherwise (standard questions), show normal image and choice cards -->
                <template x-if="!currentQuestion.gambar || !currentQuestion.gambar.includes(\'ekspresi_emosi\')">
                    <div style="width: 100%; display: flex; flex-direction: column; align-items: center; gap: 2rem;">
                        <!-- Display question image if available -->
                        <template x-if="currentQuestion.gambar">
                            <div style="width: 100%; max-width: 300px; margin: 0 auto; border-radius: 16px; overflow: hidden; background: rgba(255, 255, 255, 0.05); padding: 10px; border: 1px solid rgba(255,255,255,0.1);">
                                <img :src="currentQuestion.gambar" alt="Gambar Soal" style="width: 100%; max-height: 200px; object-fit: contain;">
                            </div>
                        </template>

                        <!-- Grid of Multiple Choice Option Cards -->
                        <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; width: 100%; margin-top: 1rem;">
                            <template x-for="(opt, idx) in currentQuestion.pilihan" :key="idx">
                                <div
                                    class="card lesson-card choice-card cursor-pointer"
                                    style="text-align: center; padding: 20px; position: relative;"
                                    :class="shakingIndex === idx ? \'animate-shake\' : \'\'"
                                    :style="getSelectedCardStyle(idx)"
                                    @click="selectOption(idx, opt)"
                                >
                                    <!-- Feedback symbols -->
                                    <div
                                        style="position: absolute; top: 10px; right: 10px; font-size: 24px;"
                                        x-html="getFeedbackSymbol(idx)"
                                    ></div>

                                    <div style="font-size: 1.6rem; font-weight: bold; color: var(--text); padding: 15px 5px;" x-text="opt"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

';

// Build new content: everything before oldStart + newBlock + everything from $oldEnd
$newContent = substr($content, 0, $startPos)
            . $newBlock
            . substr($content, $endPos + strlen("                </template>\n\n") );

// Verify we still have the feedback paragraph
if (strpos($newContent, '<p class="feedback"') === false) {
    echo "ERROR: Lost feedback paragraph!\n";
    exit(1);
}

file_put_contents($file, $newContent);
echo "SUCCESS. New file size: " . strlen($newContent) . " bytes\n";
