<?php

namespace JA\AppBundle\Model;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * News Interface
 *
 */
interface NewsInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set "title
     *
     * @param string $title
     * @return NewsInterface
     */
    public function setTitle($title);

    /**
     * Get "title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set content
     *
     * @param string $content
     * @return NewsInterface
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * Set sources
     *
     * @param string $sources
     * @return NewsInterface
     */
    public function setSources($sources);

    /**
     * Get sources
     *
     * @return string
     */
    public function getSources();

    /**
     * Set medias
     *
     * @param string $medias
     * @return NewsInterface
     */
    public function setMedias($medias);

    /**
     * Get medias
     *
     * @return string
     */
    public function getMedias();

    /**
     * Set comments
     *
     * @param string $comments
     * @return NewsInterface
     */
    public function setComments($comments);

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments();

    /**
     * Set game
     *
     * @param GameInterface $game
     * @return NewsInterface
     */
    public function setGame(GameInterface $game);

    /**
     * Get game
     *
     * @return array
     */
    public function getGame();

    /**
     * Get authors
     *
     * @return ArrayCollection
     */
    public function getAuthors();

    /**
     * Add authors
     *
     * @param UserInterface $author
     * @return NewsInterface
     */
    public function addAuthor(UserInterface $author);

    /**
     * Remove authors
     *
     * @param UserInterface $author
     */
    public function removeAuthor(UserInterface $author);

    /**
     * Get mentionedGames
     *
     * @return ArrayCollection
     */
    public function getMentionedGames();

    /**
     * Add mentionedGames
     *
     * @param GameInterface $mentionedGames
     * @return NewsInterface
     */
    public function addMentionedGame(GameInterface $mentionedGames);

    /**
     * Remove mentionedGames
     *
     * @param GameInterface $mentionedGames
     */
    public function removeMentionedGame(GameInterface $mentionedGames);
}
