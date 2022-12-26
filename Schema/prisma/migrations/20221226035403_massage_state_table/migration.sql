/*
  Warnings:

  - You are about to drop the column `state` on the `message` table. All the data in the column will be lost.

*/
-- AlterTable
ALTER TABLE `message` DROP COLUMN `state`,
    ADD COLUMN `messageStateId` INTEGER NOT NULL DEFAULT 1;

-- CreateTable
CREATE TABLE `MessageState` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `state` VARCHAR(191) NOT NULL DEFAULT 'PENDING',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- AddForeignKey
ALTER TABLE `Message` ADD CONSTRAINT `Message_messageStateId_fkey` FOREIGN KEY (`messageStateId`) REFERENCES `MessageState`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
