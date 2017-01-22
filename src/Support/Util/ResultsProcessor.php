<?php

namespace FindBrok\PersonalityInsights\Support\Util;

trait ResultsProcessor
{
    /**
     * Get Word count.
     *
     * @return int|null
     */
    public function getWordCount()
    {
        return $this->getFullProfile()->word_count;
    }

    /**
     * Get the language analysed.
     *
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getFullProfile()->processed_language;
    }

    /**
     * Get the analysis level.
     *
     * @return string
     */
    public function analysisLevel()
    {
        // Get Word count.
        $wordCount = $this->getWordCount();

        // Very Strong.
        if ($wordCount >= 6000) {
            return 'Very Strong';
        }

        // Strong analysis.
        if ($wordCount < 6000 && $wordCount >= 3500) {
            return 'Strong';
        }

        // Weak analysis.
        if ($wordCount < 3500 && $wordCount >= 100) {
            return 'Weak';
        }

        // Very weak.
        return 'Very Weak';
    }

    /**
     * Check if analysis is very strong.
     *
     * @return bool
     */
    public function isAnalysisVeryStrong()
    {
        return $this->analysisLevel() == 'Very Strong';
    }

    /**
     * Check if analysis is strong.
     *
     * @return bool
     */
    public function isAnalysisStrong()
    {
        return $this->isAnalysisVeryStrong() || $this->analysisLevel() == 'Strong';
    }

    /**
     * Check if analysis is weak.
     *
     * @return bool
     */
    public function isAnalysisWeak()
    {
        return $this->isAnalysisVeryWeak() || $this->analysisLevel() == 'Weak';
    }

    /**
     * Check if analysis is very weak.
     *
     * @return bool
     */
    public function isAnalysisVeryWeak()
    {
        return $this->analysisLevel() == 'Very Weak';
    }
}
