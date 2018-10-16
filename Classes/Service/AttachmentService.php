<?php
namespace Mittwald\Typo3Forum\Service;

use Mittwald\Typo3Forum\Domain\Model\Forum\Attachment;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class AttachmentService implements SingletonInterface
{
    /**
     * Instance of the Extbase object manager.
     *
     * @access protected
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager = null;

    /**
     * Converts HTML-array to an object
     *
     * @access public
     * @param array $attachments
     * @return ObjectStorage
     */
    public function initAttachments(array $attachments)
    {
        /* @var \Mittwald\Typo3Forum\Domain\Model\Forum\Attachment */
        $objAttachments = new ObjectStorage();

        foreach ($attachments as $attachment) {
            foreach ($attachment as $attachmentID => $eachAttachment) {
                if ($eachAttachment['name'] == '') {
                    continue;
                }
                $tmp_name = $_FILES['tx_typo3forum_pi1']['tmp_name']['attachments'][$attachmentID];

                // Save in ObjectStorage and file system
                $attachmentObj = $this->objectManager->get(Attachment::class);
                $attachmentObj->setFilename($eachAttachment['name']);
                $attachmentObj->setRealFilename(sha1($eachAttachment['name'] . time()));

                //$attachmentObj->setMimeType(mime_content_type($tmp_name));
                $attachmentObj->setMimeType($eachAttachment['type']);

                // Create directory if it does not exist
                $tca = $attachmentObj->getTCAConfig();
                $path = $tca['columns']['real_filename']['config']['uploadfolder'];
                if (!file_exists($path)) {
                    // @TODO fix insecure permissions
                    mkdir($path, 0777, true);
                }

                // Move uploaded file to final location
                $res = GeneralUtility::upload_copy_move(
                    $eachAttachment['tmp_name'],
                    $attachmentObj->getAbsoluteFilename()
                );
                if ($res === true) {
                    $objAttachments->attach($attachmentObj);
                }
            }
        }
        return $objAttachments;
    }
}
