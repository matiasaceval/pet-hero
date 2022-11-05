/*
  Warnings:

  - You are about to drop the column `id_user` on the `reservation` table. All the data in the column will be lost.
  - You are about to drop the column `keeperId` on the `review` table. All the data in the column will be lost.
  - You are about to drop the column `petId` on the `review` table. All the data in the column will be lost.
  - A unique constraint covering the columns `[reservationId]` on the table `Review` will be added. If there are existing duplicate values, this will fail.
  - Added the required column `reservationId` to the `Review` table without a default value. This is not possible if the table is not empty.

*/
-- DropForeignKey
ALTER TABLE `review` DROP FOREIGN KEY `Review_keeperId_fkey`;

-- DropForeignKey
ALTER TABLE `review` DROP FOREIGN KEY `Review_petId_fkey`;

-- AlterTable
ALTER TABLE `reservation` DROP COLUMN `id_user`;

-- AlterTable
ALTER TABLE `review` DROP COLUMN `keeperId`,
    DROP COLUMN `petId`,
    ADD COLUMN `reservationId` INTEGER NOT NULL;

-- CreateIndex
CREATE UNIQUE INDEX `Review_reservationId_key` ON `Review`(`reservationId`);

-- AddForeignKey
ALTER TABLE `Review` ADD CONSTRAINT `Review_reservationId_fkey` FOREIGN KEY (`reservationId`) REFERENCES `Reservation`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
