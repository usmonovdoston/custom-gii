<?php

namespace usmonovdoston\customGii\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "admin_gii_modules".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $token
 * @property string $add_info
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class AdminGiiModules extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_INACTIVE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_gii_modules}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'type', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'token'], 'string', 'max' => 255],
            [['add_info'],'safe'],
            [['name'],'unique'],
            [['name'],'required',
                'when' => function($model){
                    if(preg_match("/[a-zA-Z_]/+",$model->name)){
                        return true;
                    }else{
                        return false;
                    }
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'add_info' => Yii::t('app', 'Add Info'),
            'token' => Yii::t('app', 'Token'),
            'type' => Yii::t('app', 'Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    public static function getStatusList($key=null)
    {
        $list = [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', "InActive"),
        ];
        if($key){
            return $list[$key];
        }
        return $list;
    }

    public static function getModulesList(){
        $list = ['admin'=>'admin'];
        try {
            $result = self::find()->where(['status' => self::STATUS_ACTIVE])->asArray()->all();
            if(!empty($result)){
                $list = ArrayHelper::map($result,"name","name");
            }
        }catch (\Exception $e){

        }

        return $list;
    }

    public static function getModulesByToken($token, $isMultiple = false,$isMap = true)
    {
        if ($token) {
            if ($isMultiple) {
                $result = self::find()
                    ->select(['id', 'name', 'token'])
                    ->andFilterWhere(['status' => self::STATUS_ACTIVE])
                    ->andFilterWhere(['in', 'token', $token])
                    ->orderBy(['id'=>SORT_ASC])
                    ->asArray()
                    ->all();
            } else {
                $result = self::find()
                    ->select([
                        'id',
                        'CONCAT(UCASE(LEFT( name, 1)),LCASE(SUBSTRING( name, 2))) as name',
                        'token'
                    ])
                    ->andFilterWhere(['status' => self::STATUS_ACTIVE])
                    ->andFilterWhere(['token' => $token])
                    ->orderBy(['id'=>SORT_ASC])
                    ->asArray()
                    ->all();
            }
            if (!empty($result)) {
                if ($isMap){
                    return ArrayHelper::map($result, 'id', 'name');
                }else{
                    return $result;
                }
            } else return [];
        }
        return [];
    }
}
