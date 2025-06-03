<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int $is_deleted
 *
 * @property Group[] $groups
 * @property Group $parent
 */
class Group extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id'], 'default', 'value' => null],
            [['is_deleted'], 'default', 'value' => 0],
            [['name'], 'required'],
            [['parent_id', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Group::class, ['id' => 'parent_id']);
    }

    /**
     * Visszaadja a csoportok hierarchiáját tömbként, kizárva a törölt csoportokat.
     *
     * @param bool $onlyRoots Ha true, csak az ős csoportokat (parent_id NULL) adja vissza,
     *                        ha false (alapértelmezett), akkor a teljes hierarchiát.
     * @return array A csoportok hierarchiája tömb formátumban, ahol a gyerekek
     *               egy 'children' kulcs alatt találhatók.
     */
    public static function getHierarchy($onlyRoots = false)
    {
        // Lekérdezzük az összes törölt nélküli csoportot
        $groups = self::find()
            ->where(['is_deleted' => 0])
            ->orderBy(['parent_id' => SORT_ASC, 'name' => SORT_ASC])
            ->asArray()
            ->all();

        // A csoportok tömb indexelve ID alapján
        $indexedGroups = [];
        foreach ($groups as $group) {
            $group['children'] = [];
            $indexedGroups[$group['id']] = $group;
        }

        // Hierarchia felépítése
        $tree = [];
        foreach ($indexedGroups as $id => $group) {
            if ($group['parent_id'] === null) {
                // ős csoport, ide kerül
                $tree[$id] = &$indexedGroups[$id];
            } else {
                // gyerek csoport, beszúrjuk a szülő 'children' tömbjébe
                if (isset($indexedGroups[$group['parent_id']])) {
                    $indexedGroups[$group['parent_id']]['children'][] = &$indexedGroups[$id];
                }
            }
        }

        // Ha csak az ős csoportokat akarjuk, akkor visszaadjuk a fő tömböt,
        // különben a teljes hierarchiát
        if ($onlyRoots) {
            return $tree;
        }

        // Teljes hierarchia lapos tömbként (de ha csak a szülőket akarjuk, már megvolt)
        // Azonban általában a teljes hierarchia is ugyanaz, mert a gyerekek beágyazottak

        return $tree;
    }
}
